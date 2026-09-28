# 🍯 Honeypot Web - Détecteur d'Exploitations

Un site web honeypot sophistiqué qui attire les crawlers de sécurité et enregistre toutes les tentatives d'exploitation.

## 🎯 Fonctionnalités

- ✅ **Détection de chemins exploités** - Capture automatiquement les CVE, chemins sensibles
- ✅ **Faux fichiers** - Simule .env, wp-config.php, .git config, etc.
- ✅ **Alertes Telegram en temps réel** - Notification instantanée de chaque tentative
- ✅ **Logging complet** - Sauvegarde tous les tests dans `path.txt` avec JSON
- ✅ **Déploiement facile** - Docker prêt pour AWS/Heroku/VPS
- ✅ **Transparent aux crawlers** - Très difficile à détecter comme honeypot

## 🚀 Démarrage Rapide

### 1. Configuration Telegram (Obligatoire)

Créer un bot Telegram:
```bash
1. Ouvrir @BotFather sur Telegram
2. /newbot → suivre les instructions
3. Copier le TOKEN
4. Créer un groupe privé ou récupérer votre CHAT_ID avec @userinfobot
```

### 2. Installation Locale

```bash
# Cloner et setup
git clone <repo>
cd CH-CZ

# Créer l'environnement
python3 -m venv venv
source venv/bin/activate  # Sur Windows: venv\Scripts\activate

# Installer les dépendances
pip install -r requirements.txt

# Configurer les variables
cp .env.example .env
# Éditer .env avec votre TOKEN Telegram et CHAT_ID
nano .env

# Lancer le serveur
python honeypot.py
```

### 3. Test Local

```bash
# Dans un autre terminal
curl http://localhost:5000/.env
curl http://localhost:5000/wp-config.php
curl http://localhost:5000/admin

# Vérifier les logs
cat path.txt
```

### 4. Déploiement AWS (EC2)

#### Option A: Docker

```bash
# Build l'image
docker build -t honeypot .

# Lancer le container
docker run -d \
  -p 5000:5000 \
  -e TELEGRAM_BOT_TOKEN="YOUR_TOKEN" \
  -e TELEGRAM_CHAT_ID="YOUR_CHAT_ID" \
  -v /opt/honeypot/logs:/app/logs \
  --name honeypot \
  honeypot

# Vérifier
docker logs -f honeypot
```

#### Option B: AWS Elastic Beanstalk

```bash
# Créer .ebextensions/python.config
mkdir -p .ebextensions
cat > .ebextensions/python.config << 'EOF'
option_settings:
  aws:elasticbeanstalk:container:python:
    WSGIPath: honeypot:app
  aws:elasticbeanstalk:application:environment:
    TELEGRAM_BOT_TOKEN: your_token_here
    TELEGRAM_CHAT_ID: your_chat_id_here
EOF

# Déployer
eb init -p python-3.11 honeypot
eb create honeypot-prod
eb setenv TELEGRAM_BOT_TOKEN=YOUR_TOKEN TELEGRAM_CHAT_ID=YOUR_CHAT_ID
eb deploy
```

#### Option C: EC2 Classique

```bash
# Sur l'instance EC2
sudo apt update
sudo apt install -y python3-pip python3-venv git

git clone <repo>
cd CH-CZ

python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt

# Setup systemd
sudo cat > /etc/systemd/system/honeypot.service << 'EOF'
[Unit]
Description=Web Honeypot
After=network.target

[Service]
Type=simple
User=ubuntu
WorkingDirectory=/home/ubuntu/CH-CZ
Environment="TELEGRAM_BOT_TOKEN=YOUR_TOKEN"
Environment="TELEGRAM_CHAT_ID=YOUR_CHAT_ID"
ExecStart=/home/ubuntu/CH-CZ/venv/bin/gunicorn --bind 0.0.0.0:5000 --workers 4 honeypot:app
Restart=always

[Install]
WantedBy=multi-user.target
EOF

sudo systemctl enable honeypot
sudo systemctl start honeypot
sudo systemctl status honeypot
```

## 📊 Chemins Détectés

Le honeypot capture automatiquement les tentatives pour:

### Fichiers Sensibles
- `.env`, `.env.local`, `.env.bak`
- `wp-config.php`, `web.config`
- `.git/config`, `.git/HEAD`
- Clés SSH, tokens privés

### Endpoints Administrateur
- `/admin`, `/administrator`, `/cpanel`
- `/phpmyadmin`, `/control_panel`
- `/api/v1/admin`, `/api/admin/users`

### Backdoors/Webshells
- `shell.php`, `c99.php`, `webshell.php`
- Détection d'upload suspect

### CVEs et Exploits
- Patterns CVE-2021/2022/2023/2024
- Chemins d'exploitation connus

## 📝 Format des Logs

Fichier `path.txt`:
```json
{"timestamp": "2024-01-15T10:23:45.123456", "path": "/.env", "method": "GET", "ip": "192.168.1.100", "user_agent": "Mozilla/5.0...", "status": 200, "type": "HONEYPOT_MATCH"}
{"timestamp": "2024-01-15T10:24:12.654321", "path": "/wp-config.php", "method": "POST", "ip": "10.0.0.50", "user_agent": "curl/7.68.0", "status": 200, "type": "HONEYPOT_MATCH"}
```

### Analyse des Logs

```bash
# Compter les tentatives par IP
cat path.txt | jq -r '.ip' | sort | uniq -c | sort -rn

# Voir tous les .env accédés
cat path.txt | jq 'select(.path | contains(".env"))'

# Checker les exploits tentés
cat path.txt | jq 'select(.type == "HONEYPOT_MATCH")'

# Timeline des attaques
cat path.txt | jq -r '[.timestamp, .path, .ip] | @csv' | sort
```

## 🔔 Notifications Telegram

À chaque tentative d'exploitation, un message comme:

```
🚨 HONEYPOT ALERT 🚨

Heure: 2024-01-15T10:23:45.123456
Chemin: /.env
Méthode: GET
IP: 192.168.1.100
Status: 200
User-Agent: curl/7.68.0
```

## 🛡️ Sécurité

### Protéger le Honeypot

1. **Ne pas exposer les logs publiquement**
   ```bash
   chmod 600 path.txt
   ```

2. **Utiliser HTTPS en production**
   ```bash
   # Avec Let's Encrypt sur EC2
   sudo apt install certbot python3-certbot-nginx
   sudo certbot certonly --standalone -d honeypot.votresite.com
   ```

3. **Rate limiting** - Ajouter avec Nginx en reverse proxy
   ```nginx
   limit_req_zone $binary_remote_addr zone=honeypot:10m rate=100r/m;
   location / {
       limit_req zone=honeypot burst=200 nodelay;
       proxy_pass http://localhost:5000;
   }
   ```

4. **Masquer l'indicateur honeypot**
   ```python
   # Retirer les headers révélateurs
   @app.after_request
   def remove_headers(response):
       response.headers.pop('Server', None)
       return response
   ```

## 📈 Statistiques Utiles

```bash
# Attaquants uniques par jour
cat path.txt | jq -r '.timestamp' | cut -d'T' -f1 | sort | uniq -c

# Chemins les plus tentés
cat path.txt | jq -r '.path' | sort | uniq -c | sort -rn | head -20

# Tentatives par méthode HTTP
cat path.txt | jq -r '.method' | sort | uniq -c

# Top 10 IPs attaquantes
cat path.txt | jq -r '.ip' | sort | uniq -c | sort -rn | head -10

# Détail d'une IP
cat path.txt | jq "select(.ip == \"192.168.1.100\")"
```

## 🔍 Intégration avec d'autres Outils

### Slack au lieu de Telegram
```python
# Remplacer send_telegram_alert() par:
def send_slack_alert(path, ip):
    webhook_url = os.getenv("SLACK_WEBHOOK")
    requests.post(webhook_url, json={
        "text": f"🚨 Honeypot Alert: {path} from {ip}"
    })
```

### ELK Stack (ElasticSearch + Kibana)
```python
from elasticsearch import Elasticsearch
es = Elasticsearch([os.getenv("ELASTICSEARCH_URL")])
# Dans log_path():
es.index(index="honeypot", body=log_entry)
```

### CloudWatch (AWS Monitoring)
```python
import boto3
cloudwatch = boto3.client('logs', region_name='eu-west-1')
# Dans log_path():
cloudwatch.put_log_events(...)
```

## ⚙️ Configuration Avancée

### Personnaliser les faux fichiers
Éditer la section `FAKE_*_CONTENT` dans `honeypot.py` pour simuler de vrais fichiers sensibles.

### Ajouter des chemins custom
```python
HONEYPOT_PATHS.extend([
    "votre_chemin_secret",
    "another/sensitive/path"
])
```

### Webhook Custom
```python
def send_webhook(data):
    requests.post("https://votre-api.com/honeypot", json=data)
```

## 🐛 Dépannage

### Telegram ne reçoit pas d'alertes
```bash
# Tester l'API Telegram
curl -X POST "https://api.telegram.org/bot{TOKEN}/sendMessage" \
  -H "Content-Type: application/json" \
  -d '{"chat_id": "{CHAT_ID}", "text": "Test"}'
```

### Logs vides
```bash
# Vérifier que le fichier est accessible
ls -la path.txt
tail -f path.txt
```

### Port 5000 déjà utilisé
```bash
# Trouver le processus
lsof -i :5000
# Tuer ou changer de port dans honeypot.py
```

## 📚 Ressources

- [OWASP - Honeypots](https://owasp.org/www-community/attacks/Honeypot)
- [Telegram Bot API](https://core.telegram.org/bots/api)
- [AWS Elastic Beanstalk](https://docs.aws.amazon.com/elasticbeanstalk/)

---

**Créé pour la détection et l'analyse des menaces. À utiliser conformément aux lois locales. ⚖️**
