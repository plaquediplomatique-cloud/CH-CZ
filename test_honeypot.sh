#!/bin/bash
# Script de test du honeypot - Simule des attaques courantes

set -e

HONEYPOT_URL="${HONEYPOT_URL:-http://localhost:5000}"

echo "🧪 Test du Honeypot"
echo "==================="
echo "URL: $HONEYPOT_URL"
echo ""

# Couleurs
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

# Vérifier que le honeypot est en ligne
echo -e "${YELLOW}Vérification de la connexion...${NC}"
if ! curl -s "$HONEYPOT_URL/health" | grep -q "ok"; then
    echo "❌ Le honeypot ne répond pas sur $HONEYPOT_URL"
    exit 1
fi
echo -e "${GREEN}✅ Honeypot en ligne${NC}\n"

# Tests des fichiers sensibles
echo -e "${YELLOW}Test 1: Fichiers sensibles${NC}"
test_endpoints=(
    ".env"
    ".env.local"
    ".env.bak"
    "wp-config.php"
    ".git/config"
    ".git/HEAD"
    "web.config"
    "config.php"
)

for endpoint in "${test_endpoints[@]}"; do
    echo -n "   Test /$endpoint ... "
    response=$(curl -s -o /dev/null -w "%{http_code}" "$HONEYPOT_URL/$endpoint")
    echo -e "${GREEN}[$response]${NC}"
done

echo ""
echo -e "${YELLOW}Test 2: Chemins administrateur${NC}"
admin_paths=(
    "admin"
    "administrator"
    "cpanel"
    "phpmyadmin"
    "api/v1/admin"
)

for path in "${admin_paths[@]}"; do
    echo -n "   Test /$path ... "
    response=$(curl -s -o /dev/null -w "%{http_code}" "$HONEYPOT_URL/$path")
    echo -e "${GREEN}[$response]${NC}"
done

echo ""
echo -e "${YELLOW}Test 3: Backdoors/Webshells${NC}"
shells=(
    "shell.php"
    "c99.php"
    "webshell.php"
)

for shell in "${shells[@]}"; do
    echo -n "   Test /$shell ... "
    response=$(curl -s -o /dev/null -w "%{http_code}" "$HONEYPOT_URL/$shell")
    echo -e "${GREEN}[$response]${NC}"
done

echo ""
echo -e "${YELLOW}Test 4: Chemins CVE${NC}"
cve_paths=(
    "CVE-2021-1234"
    "CVE-2022-5678"
    "CVE-2023-9999"
)

for cve in "${cve_paths[@]}"; do
    echo -n "   Test /$cve ... "
    response=$(curl -s -o /dev/null -w "%{http_code}" "$HONEYPOT_URL/$cve")
    echo -e "${GREEN}[$response]${NC}"
done

echo ""
echo -e "${YELLOW}Test 5: Divers chemins${NC}"
misc_paths=(
    "backup"
    "backup.sql"
    "database.sql"
    "password.txt"
    "upload"
    "test.php"
)

for path in "${misc_paths[@]}"; do
    echo -n "   Test /$path ... "
    response=$(curl -s -o /dev/null -w "%{http_code}" "$HONEYPOT_URL/$path")
    echo -e "${GREEN}[$response]${NC}"
done

echo ""
echo -e "${YELLOW}Test 6: Tests avec User-Agent suspect${NC}"
echo -n "   Test avec Nikto ... "
curl -s -A "Nikto/2.1.5" "$HONEYPOT_URL/.env" > /dev/null
echo -e "${GREEN}✓${NC}"

echo -n "   Test avec SQLMap ... "
curl -s -A "sqlmap/1.5.2" "$HONEYPOT_URL/admin" > /dev/null
echo -e "${GREEN}✓${NC}"

echo -n "   Test avec nmap ... "
curl -s -A "Mozilla (compatible; nmap Scripting Engine)" "$HONEYPOT_URL/wp-config.php" > /dev/null
echo -e "${GREEN}✓${NC}"

echo ""
echo -e "${YELLOW}Test 7: Vérification des logs${NC}"
if [ -f "path.txt" ]; then
    count=$(wc -l < path.txt)
    echo -e "   ${GREEN}✓${NC} Fichier path.txt existe"
    echo "     Nombre d'entrées: $count"
    echo ""
    echo "   Dernières entrées:"
    tail -5 path.txt | while read line; do
        echo "     $line"
    done
else
    echo "   ❌ Fichier path.txt non trouvé"
fi

echo ""
echo -e "${GREEN}✅ Tests terminés!${NC}"
echo ""
echo "💡 Conseil: Exécuter ce script plusieurs fois pour simuler des attaques répétées"
echo "📊 Analyser les logs avec: python3 analyze_logs.py"
