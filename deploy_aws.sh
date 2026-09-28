#!/bin/bash
# Script de déploiement du Honeypot sur AWS EC2

set -e

echo "🚀 Déploiement Honeypot sur AWS EC2"
echo "===================================="

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Configuration
INSTANCE_USER="${INSTANCE_USER:-ubuntu}"
INSTANCE_IP="${INSTANCE_IP:-}"
INSTANCE_KEY="${INSTANCE_KEY:-}"
APP_DIR="/home/ubuntu/honeypot"
SERVICE_NAME="honeypot"

# Vérifier les arguments
if [ -z "$INSTANCE_IP" ] || [ -z "$INSTANCE_KEY" ]; then
    echo -e "${RED}❌ Usage: INSTANCE_IP=xxx INSTANCE_KEY=xxx deploy_aws.sh${NC}"
    echo "   Exemples:"
    echo "   INSTANCE_IP=54.123.45.67 INSTANCE_KEY=~/.ssh/honeypot.pem bash deploy_aws.sh"
    exit 1
fi

# Fonction pour exécuter des commandes SSH
ssh_exec() {
    ssh -i "$INSTANCE_KEY" -o StrictHostKeyChecking=no "$INSTANCE_USER@$INSTANCE_IP" "$@"
}

# Fonction pour copier des fichiers
scp_copy() {
    scp -i "$INSTANCE_KEY" -o StrictHostKeyChecking=no -r "$1" "$INSTANCE_USER@$INSTANCE_IP:$2"
}

echo -e "${YELLOW}1️⃣  Mise à jour du système${NC}"
ssh_exec "sudo apt-get update && sudo apt-get upgrade -y"

echo -e "${YELLOW}2️⃣  Installation des dépendances${NC}"
ssh_exec "sudo apt-get install -y python3-pip python3-venv git curl wget"

echo -e "${YELLOW}3️⃣  Création du répertoire de l'app${NC}"
ssh_exec "mkdir -p $APP_DIR && cd $APP_DIR"

echo -e "${YELLOW}4️⃣  Copie des fichiers de l'application${NC}"
scp_copy "honeypot.py" "$APP_DIR/"
scp_copy "requirements.txt" "$APP_DIR/"
scp_copy ".env.example" "$APP_DIR/"

echo -e "${YELLOW}5️⃣  Setup de l'environnement Python${NC}"
ssh_exec "cd $APP_DIR && python3 -m venv venv"
ssh_exec "cd $APP_DIR && source venv/bin/activate && pip install -r requirements.txt"

echo -e "${YELLOW}6️⃣  Configuration du fichier .env${NC}"
ssh_exec "cp $APP_DIR/.env.example $APP_DIR/.env"
echo -e "${RED}⚠️  IMPORTANT: Éditer le fichier .env sur le serveur avec vos credentials Telegram:${NC}"
echo "    ssh -i $INSTANCE_KEY $INSTANCE_USER@$INSTANCE_IP"
echo "    nano $APP_DIR/.env"
echo ""

# Demander confirmation
read -p "Appuyez sur Entrée après avoir configuré .env sur le serveur..."

echo -e "${YELLOW}7️⃣  Création du service systemd${NC}"
ssh_exec "sudo tee /etc/systemd/system/$SERVICE_NAME.service > /dev/null << 'EOF'
[Unit]
Description=Web Honeypot - Security Threat Detection
After=network.target
Wants=network-online.target

[Service]
Type=simple
User=ubuntu
WorkingDirectory=$APP_DIR
EnvironmentFile=$APP_DIR/.env
ExecStart=$APP_DIR/venv/bin/gunicorn --bind 0.0.0.0:5000 --workers 4 --timeout 120 --access-logfile - --error-logfile - honeypot:app
Restart=always
RestartSec=10
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
EOF"

echo -e "${YELLOW}8️⃣  Activation et démarrage du service${NC}"
ssh_exec "sudo systemctl daemon-reload"
ssh_exec "sudo systemctl enable $SERVICE_NAME"
ssh_exec "sudo systemctl start $SERVICE_NAME"

echo -e "${YELLOW}9️⃣  Vérification du status${NC}"
ssh_exec "sudo systemctl status $SERVICE_NAME --no-pager" || true

echo -e "${YELLOW}🔟 Setup de Nginx (reverse proxy)${NC}"
ssh_exec "sudo apt-get install -y nginx"

ssh_exec "sudo tee /etc/nginx/sites-available/honeypot > /dev/null << 'EOF'
server {
    listen 80;
    server_name _;

    client_max_body_size 100M;

    # Rate limiting
    limit_req_zone \$binary_remote_addr zone=honeypot:10m rate=100r/m;

    location / {
        limit_req zone=honeypot burst=200 nodelay;
        proxy_pass http://127.0.0.1:5000;
        proxy_set_header Host \$host;
        proxy_set_header X-Real-IP \$remote_addr;
        proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto \$scheme;
        proxy_redirect off;

        # Timeouts
        proxy_connect_timeout 60s;
        proxy_send_timeout 60s;
        proxy_read_timeout 60s;
    }

    # Masquer les headers révélateurs
    server_tokens off;
}
EOF"

ssh_exec "sudo ln -sf /etc/nginx/sites-available/honeypot /etc/nginx/sites-enabled/"
ssh_exec "sudo rm -f /etc/nginx/sites-enabled/default"
ssh_exec "sudo nginx -t"
ssh_exec "sudo systemctl enable nginx"
ssh_exec "sudo systemctl restart nginx"

echo -e "${YELLOW}1️⃣1️⃣ Setup SSL/HTTPS (Let's Encrypt)${NC}"
ssh_exec "sudo apt-get install -y certbot python3-certbot-nginx"
echo -e "${YELLOW}Pour activer HTTPS: ssh -i $INSTANCE_KEY $INSTANCE_USER@$INSTANCE_IP${NC}"
echo "    sudo certbot certonly --standalone -d votre-domaine.com"
echo ""

echo -e "${YELLOW}1️⃣2️⃣ Configuration des logs${NC}"
ssh_exec "sudo chown ubuntu:ubuntu $APP_DIR"
ssh_exec "mkdir -p $APP_DIR/logs"
ssh_exec "chmod 755 $APP_DIR/logs"

echo -e "${YELLOW}1️⃣3️⃣ Verification du honeypot${NC}"
sleep 2
if curl -s http://$INSTANCE_IP/.env | grep -q "DB_HOST"; then
    echo -e "${GREEN}✅ Honeypot fonctionne!${NC}"
else
    echo -e "${RED}❌ Vérification échouée, checker avec: ssh -i $INSTANCE_KEY $INSTANCE_USER@$INSTANCE_IP${NC}"
    ssh_exec "sudo systemctl status $SERVICE_NAME"
fi

echo -e "\n${GREEN}✅ DÉPLOIEMENT TERMINÉ!${NC}\n"

echo "📊 Commandes utiles:"
echo "   SSH:              ssh -i $INSTANCE_KEY $INSTANCE_USER@$INSTANCE_IP"
echo "   Status:           ssh -i $INSTANCE_KEY $INSTANCE_USER@$INSTANCE_IP sudo systemctl status honeypot"
echo "   Logs en temps réel: ssh -i $INSTANCE_KEY $INSTANCE_USER@$INSTANCE_IP sudo journalctl -f -u honeypot"
echo "   Afficher path.txt: ssh -i $INSTANCE_KEY $INSTANCE_USER@$INSTANCE_IP cat /home/ubuntu/honeypot/path.txt | head -20"
echo "   Analyser logs:    ssh -i $INSTANCE_KEY $INSTANCE_USER@$INSTANCE_IP python3 /home/ubuntu/honeypot/analyze_logs.py"
echo ""
echo "🔗 URL du honeypot: http://$INSTANCE_IP"
echo "📝 Configuration: $APP_DIR/.env"
echo "📊 Logs: $APP_DIR/path.txt"
echo ""
echo -e "${YELLOW}N'oublie pas de:${NC}"
echo "  1. Configurer un domaine pointant vers $INSTANCE_IP"
echo "  2. Activer HTTPS avec Let's Encrypt"
echo "  3. Configurer les notifications Telegram"
echo "  4. Vérifier les logs régulièrement"
