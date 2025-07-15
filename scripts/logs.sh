#!/bin/bash

# Script pour visualiser les logs du CRM Investisseurs

set -e

# Variables
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"

# Fonction pour utiliser docker-compose ou docker compose
docker_compose_cmd() {
    if command -v docker-compose &> /dev/null; then
        echo "docker-compose"
    else
        echo "docker compose"
    fi
}

# Affichage de l'aide
show_help() {
    echo "Usage: $0 [service] [options]"
    echo ""
    echo "Services disponibles:"
    echo "  app        Application PHP-FPM"
    echo "  nginx      Serveur web Nginx"
    echo "  postgres   Base de données PostgreSQL"
    echo "  pgadmin    Interface pgAdmin"
    echo "  mailpit    Serveur SMTP de test"
    echo "  redis      Cache Redis"
    echo "  node       Serveur de développement Vite"
    echo ""
    echo "Options:"
    echo "  -f, --follow    Suit les logs en temps réel"
    echo "  --tail=N        Affiche les N dernières lignes (défaut: 100)"
    echo "  --help          Affiche cette aide"
    echo ""
    echo "Exemples:"
    echo "  $0                    # Tous les logs"
    echo "  $0 app --follow       # Logs de l'app en temps réel"
    echo "  $0 nginx --tail=50    # 50 dernières lignes nginx"
}

# Script principal
main() {
    cd "$PROJECT_DIR"

    local service=""
    local follow=""
    local tail="100"
    local compose_cmd=$(docker_compose_cmd)

    # Analyse des arguments
    while [[ $# -gt 0 ]]; do
        case $1 in
            app|nginx|postgres|pgadmin|mailpit|redis|node)
                service="$1"
                shift
                ;;
            -f|--follow)
                follow="--follow"
                shift
                ;;
            --tail=*)
                tail="${1#*=}"
                shift
                ;;
            --help|help)
                show_help
                exit 0
                ;;
            *)
                echo "Option inconnue: $1"
                show_help
                exit 1
                ;;
        esac
    done

    # Construction de la commande
    if [ -n "$service" ]; then
        echo "📋 Logs du service: $service"
        $compose_cmd logs --tail="$tail" $follow "$service"
    else
        echo "📋 Logs de tous les services"
        $compose_cmd logs --tail="$tail" $follow
    fi
}

main "$@"
