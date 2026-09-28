#!/usr/bin/env python3
"""
Script d'analyse des logs du honeypot
Génère des statistiques et rapports de sécurité
"""

import json
import sys
from collections import defaultdict
from pathlib import Path
from datetime import datetime
from tabulate import tabulate

LOG_FILE = "path.txt"


def load_logs():
    """Charge les logs depuis path.txt"""
    logs = []
    if not Path(LOG_FILE).exists():
        print(f"❌ Fichier {LOG_FILE} non trouvé")
        return logs

    with open(LOG_FILE, "r") as f:
        for line in f:
            try:
                logs.append(json.loads(line.strip()))
            except json.JSONDecodeError:
                continue
    return logs


def get_top_ips(logs, limit=10):
    """IPs les plus actives"""
    ip_count = defaultdict(int)
    for log in logs:
        ip_count[log["ip"]] += 1

    sorted_ips = sorted(ip_count.items(), key=lambda x: x[1], reverse=True)
    return sorted_ips[:limit]


def get_top_paths(logs, limit=10):
    """Chemins les plus testés"""
    path_count = defaultdict(int)
    for log in logs:
        path_count[log["path"]] += 1

    sorted_paths = sorted(path_count.items(), key=lambda x: x[1], reverse=True)
    return sorted_paths[:limit]


def get_attack_types(logs):
    """Catégories d'attaques détectées"""
    types = defaultdict(int)
    for log in logs:
        types[log.get("type", "UNKNOWN")] += 1
    return sorted(types.items(), key=lambda x: x[1], reverse=True)


def get_methods(logs):
    """Méthodes HTTP utilisées"""
    methods = defaultdict(int)
    for log in logs:
        methods[log.get("method", "UNKNOWN")] += 1
    return sorted(methods.items(), key=lambda x: x[1], reverse=True)


def get_timeline(logs):
    """Attaques par jour"""
    timeline = defaultdict(int)
    for log in logs:
        date = log["timestamp"].split("T")[0]
        timeline[date] += 1
    return sorted(timeline.items())


def get_suspicious_agents(logs):
    """User-Agents suspects détectés"""
    agents = defaultdict(int)
    suspicious_keywords = ["bot", "crawler", "scan", "nikto", "sqlmap", "nmap", "exploit"]

    for log in logs:
        ua = log.get("user_agent", "").lower()
        for keyword in suspicious_keywords:
            if keyword in ua:
                agents[log.get("user_agent", "Unknown")[:60]] += 1
                break

    return sorted(agents.items(), key=lambda x: x[1], reverse=True)


def get_sensitive_files_accessed(logs):
    """Fichiers sensibles les plus ciblés"""
    sensitive = []
    sensitive_patterns = [".env", ".git", "wp-config", "secret", "key", "password", "backup", "sql"]

    for log in logs:
        path = log["path"].lower()
        for pattern in sensitive_patterns:
            if pattern in path:
                sensitive.append((log["path"], log["ip"], log["timestamp"]))
                break

    return sensitive


def print_report(logs):
    """Génère un rapport complet"""
    if not logs:
        print("❌ Aucun log disponible")
        return

    print("\n" + "="*60)
    print("🍯  RAPPORT HONEYPOT - ANALYSE DES MENACES")
    print("="*60)

    # Stats générales
    print(f"\n📊 STATISTIQUES GÉNÉRALES")
    print(f"   Total tentatives: {len(logs)}")
    print(f"   Période: {logs[0]['timestamp']} à {logs[-1]['timestamp']}")
    print(f"   IPs uniques: {len(set(log['ip'] for log in logs))}")
    print(f"   Chemins uniques testés: {len(set(log['path'] for log in logs))}")

    # Top IPs
    print(f"\n🎯 TOP 10 IPs ATTAQUANTES")
    top_ips = get_top_ips(logs)
    for i, (ip, count) in enumerate(top_ips, 1):
        print(f"   {i:2d}. {ip:20s} | {count:5d} tentatives")

    # Top chemins
    print(f"\n🔓 TOP 10 CHEMINS TESTÉS")
    top_paths = get_top_paths(logs)
    for i, (path, count) in enumerate(top_paths, 1):
        status = "🚨 SENSIBLE" if any(x in path.lower() for x in [".env", ".git", "wp", "admin"]) else ""
        print(f"   {i:2d}. {path:30s} | {count:5d} fois {status}")

    # Méthodes HTTP
    print(f"\n📤 MÉTHODES HTTP")
    methods = get_methods(logs)
    for method, count in methods:
        print(f"   {method:10s}: {count:5d}")

    # Types d'attaques
    print(f"\n⚔️  TYPES D'ATTAQUES DÉTECTÉES")
    attack_types = get_attack_types(logs)
    for atype, count in attack_types:
        print(f"   {atype:20s}: {count:5d}")

    # Fichiers sensibles accédés
    print(f"\n🔒 FICHIERS SENSIBLES CIBLÉS")
    sensitive = get_sensitive_files_accessed(logs)
    for path, ip, timestamp in sensitive[:15]:
        print(f"   {path:25s} | {ip:15s} | {timestamp}")
    if len(sensitive) > 15:
        print(f"   ... et {len(sensitive) - 15} autres tentatives")

    # User-Agents suspects
    print(f"\n🤖 USER-AGENTS SUSPECTS")
    agents = get_suspicious_agents(logs)
    for agent, count in agents[:10]:
        print(f"   {agent:50s} | {count:3d}")

    # Timeline
    print(f"\n📅 ATTAQUES PAR JOUR")
    timeline = get_timeline(logs)
    for date, count in timeline:
        bar = "█" * min(count // 5, 30)
        print(f"   {date}: {bar} ({count})")

    print("\n" + "="*60 + "\n")


def export_csv(logs):
    """Exporte les logs en CSV"""
    output_file = "honeypot_export.csv"
    with open(output_file, "w") as f:
        f.write("timestamp,path,method,ip,user_agent,status,type\n")
        for log in logs:
            f.write(f"{log['timestamp']},{log['path']},{log['method']},{log['ip']},\"{log['user_agent']}\",{log['status']},{log.get('type', 'UNKNOWN')}\n")
    print(f"✅ Exporté en {output_file}")


def export_json(logs):
    """Exporte les logs en JSON structuré"""
    output_file = "honeypot_export.json"
    summary = {
        "total_attempts": len(logs),
        "unique_ips": len(set(log['ip'] for log in logs)),
        "unique_paths": len(set(log['path'] for log in logs)),
        "top_ips": dict(get_top_ips(logs)),
        "top_paths": dict(get_top_paths(logs)),
        "attack_types": dict(get_attack_types(logs)),
        "methods": dict(get_methods(logs)),
        "raw_logs": logs
    }
    with open(output_file, "w") as f:
        json.dump(summary, f, indent=2)
    print(f"✅ Exporté en {output_file}")


def main():
    logs = load_logs()

    if len(sys.argv) > 1:
        command = sys.argv[1]
        if command == "--csv":
            export_csv(logs)
        elif command == "--json":
            export_json(logs)
        elif command == "--top-ips":
            for ip, count in get_top_ips(logs, 50):
                print(f"{ip},{count}")
        elif command == "--top-paths":
            for path, count in get_top_paths(logs, 50):
                print(f"{path},{count}")
        else:
            print(f"❌ Commande inconnue: {command}")
            print("Usage: python analyze_logs.py [--csv|--json|--top-ips|--top-paths]")
    else:
        print_report(logs)


if __name__ == "__main__":
    main()
