#!/usr/bin/env python3
"""
Honeypot Web Server - Détecte et log les tentatives d'exploitation
Sauvegarde les chemins testés et envoie des alertes Telegram
"""

import os
import json
import requests
from datetime import datetime
from flask import Flask, request, jsonify
from pathlib import Path
from urllib.parse import unquote
import logging

app = Flask(__name__)

# Configuration
LOG_FILE = "path.txt"
TELEGRAM_BOT_TOKEN = os.getenv("TELEGRAM_BOT_TOKEN", "YOUR_BOT_TOKEN")
TELEGRAM_CHAT_ID = os.getenv("TELEGRAM_CHAT_ID", "YOUR_CHAT_ID")
FAKE_ENV_CONTENT = """DB_HOST=prod-db.internal.aws
DB_USER=admin_prod
DB_PASS=C0mpl3x!P@ssw0rd2024
AWS_ACCESS_KEY=AKIA234567890ABCDEF
AWS_SECRET_KEY=wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY
ADMIN_TOKEN=sk_live_51234567890abcdefghijk
API_KEY=68c5d8f4e3b2a1f9g8h7i6j5k4l3m2n1
DATABASE_URL=postgresql://user:pass@db.prod.local:5432/maindb
STRIPE_KEY=sk_live_abc123def456
"""

# Setup logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

# Common paths attacked by bots/scanners
HONEYPOT_PATHS = [
    # CVE/Exploit paths
    ".env", ".env.local", ".env.bak", ".env.old",
    "config.php", "admin.php", "wp-admin.php",
    ".git/config", ".git/HEAD", ".gitignore",
    ".git/packed-refs", ".git/objects/pack",
    "wp-config.php", "wp-config.php.bak", "wp-config.php.swp",
    "web.config", "web.config.bak",
    "console", "admin", "administrator",
    "phpmyadmin", "cpanel", "control_panel",
    "api/v1/admin", "api/admin/users",
    "backup", "backups", "backup.sql", "backup.zip",
    "database.sql", "db.sql", "dump.sql",
    "password.txt", "passwords.txt", "credentials.txt",
    "secret.key", "private.key", "id_rsa",
    "swagger", "swagger.json", "api-docs",
    "actuator", "actuator/health",
    ".well-known/acme-challenge",
    "xmlrpc.php",
    "wp-json", "wp-json/wp/v2/users",
    "c99.php", "shell.php", "webshell.php",
    "upload", "uploads", "files",
    "test.php", "index.bak", "index.html.bak",
    "wordpress", "wp", "blog",
    "joomla", "administrator",
    "user", "users", "admin/users",
    "api", "api/v1", "api/v2",
    "config", "configuration",
    "install", "setup",
    "trace", "debug",
    "metrics", "health",
    ".DS_Store",
    "thumbs.db",
    ".svn", ".svn/entries",
    "CVE-2021", "CVE-2022", "CVE-2023", "CVE-2024",
    "shell", "webshell",
]


def send_telegram_alert(path, user_agent, ip, method, status_code):
    """Envoie une alerte Telegram"""
    if TELEGRAM_BOT_TOKEN == "YOUR_BOT_TOKEN":
        logger.warning("Telegram non configuré - configure TELEGRAM_BOT_TOKEN et TELEGRAM_CHAT_ID")
        return

    try:
        timestamp = datetime.now().isoformat()
        message = f"""
🚨 **HONEYPOT ALERT** 🚨

**Heure:** {timestamp}
**Chemin:** `{path}`
**Méthode:** {method}
**IP:** {ip}
**Status:** {status_code}
**User-Agent:** `{user_agent[:100]}`
"""

        url = f"https://api.telegram.org/bot{TELEGRAM_BOT_TOKEN}/sendMessage"
        payload = {
            "chat_id": TELEGRAM_CHAT_ID,
            "text": message,
            "parse_mode": "Markdown"
        }
        requests.post(url, json=payload, timeout=5)
        logger.info(f"Alerte Telegram envoyée pour {path}")
    except Exception as e:
        logger.error(f"Erreur Telegram: {e}")


def log_path(path, user_agent, ip, method, status_code, response_type=""):
    """Enregistre le chemin accédé"""
    timestamp = datetime.now().isoformat()
    log_entry = {
        "timestamp": timestamp,
        "path": path,
        "method": method,
        "ip": ip,
        "user_agent": user_agent,
        "status": status_code,
        "type": response_type
    }

    # Ajouter au fichier path.txt
    with open(LOG_FILE, "a") as f:
        f.write(json.dumps(log_entry) + "\n")

    logger.info(f"[{ip}] {method} {path} - {status_code} ({response_type})")

    # Envoyer alerte Telegram
    send_telegram_alert(path, user_agent, ip, method, status_code)


@app.before_request
def check_honeypot_path():
    """Intercepte les requêtes suspectes"""
    path = unquote(request.path)
    ip = request.remote_addr
    user_agent = request.headers.get("User-Agent", "Unknown")
    method = request.method

    # Vérifier si c'est un path honeypot
    path_lower = path.lower().strip("/")

    for honeypot in HONEYPOT_PATHS:
        if honeypot.lower() in path_lower:
            log_path(path, user_agent, ip, method, 200, "HONEYPOT_MATCH")
            return handle_honeypot_request(path, honeypot)


def handle_honeypot_request(path, honeypot_type):
    """Génère une fausse réponse selon le type de fichier"""
    path_lower = path.lower()

    # .env file
    if ".env" in path_lower:
        return FAKE_ENV_CONTENT, 200, {"Content-Type": "text/plain"}

    # Git config
    if ".git" in path_lower:
        if "config" in path_lower:
            return """[core]
\trepositoryformatversion = 0
\tfilemode = true
\tbare = false
[remote "origin"]
\turl = git@github.com:company/secret-repo.git
[branch "master"]
\tremote = origin
\tmerge = refs/heads/master""", 200, {"Content-Type": "text/plain"}
        elif "head" in path_lower:
            return "ref: refs/heads/main\n", 200
        else:
            return "Forbidden", 403

    # WordPress config
    if "wp-config" in path_lower:
        return """<?php
define('DB_NAME', 'wordpress_db');
define('DB_USER', 'wp_admin');
define('DB_PASSWORD', 'V3ryStr0ng!P@ss');
define('DB_HOST', 'localhost');
define('WORDPRESS_VERSION', '6.4');
define('AUTH_KEY', 'put your unique phrase here');
""", 200, {"Content-Type": "text/plain"}

    # Admin endpoints
    if "admin" in path_lower or "administrator" in path_lower:
        return jsonify({
            "error": "Unauthorized",
            "code": 401,
            "message": "Authentication required"
        }), 401

    # SQL backups
    if ".sql" in path_lower or "backup" in path_lower:
        return "-- MySQL dump\n-- Database: production\nINSERT INTO users VALUES...", 200

    # Shell/WebShell
    if any(shell in path_lower for shell in ["shell", "webshell", "c99", ".php"]):
        log_path(path, request.headers.get("User-Agent", ""), request.remote_addr, request.method, 403, "WEBSHELL_ATTEMPT")
        return "Access Denied", 403

    # API endpoints
    if "/api" in path_lower:
        return jsonify({
            "status": "error",
            "message": "Invalid endpoint"
        }), 404

    # Default fake response
    return jsonify({
        "error": "Not Found",
        "code": 404,
        "path": path
    }), 404


@app.route("/", defaults={"path": ""})
@app.route("/<path:path>", methods=["GET", "POST", "PUT", "DELETE", "PATCH", "HEAD", "OPTIONS"])
def catch_all(path):
    """Capture toutes les requêtes"""
    path = f"/{path}" if path else "/"
    ip = request.remote_addr
    user_agent = request.headers.get("User-Agent", "Unknown")
    method = request.method

    log_path(path, user_agent, ip, method, 404, "UNKNOWN_PATH")

    return jsonify({
        "error": "Not Found",
        "code": 404,
        "message": "This resource does not exist"
    }), 404


@app.route("/health", methods=["GET"])
def health():
    """Endpoint de santé du honeypot"""
    return jsonify({"status": "ok", "honeypot": True}), 200


@app.errorhandler(404)
def not_found(e):
    return catch_all("")


if __name__ == "__main__":
    # Créer le fichier log s'il n'existe pas
    Path(LOG_FILE).touch()

    # Afficher la configuration
    print("""
    ╔════════════════════════════════════════╗
    ║        WEB HONEYPOT - DÉMARRAGE        ║
    ╚════════════════════════════════════════╝

    📝 Log file: {LOG_FILE}
    🤖 Telegram Bot Token: {'✓ Configuré' if TELEGRAM_BOT_TOKEN != 'YOUR_BOT_TOKEN' else '✗ Non configuré'}
    💬 Telegram Chat ID: {'✓ Configuré' if TELEGRAM_CHAT_ID != 'YOUR_CHAT_ID' else '✗ Non configuré'}
    🔗 Server: http://0.0.0.0:5000
    """.format(LOG_FILE=LOG_FILE))

    app.run(host="0.0.0.0", port=5000, debug=False)
