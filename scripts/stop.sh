#!/bin/bash

# Script d'arrêt pour CRM Investisseurs

set -e

# Couleurs pour l'output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Variables
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"

# Fonctions utilitaires
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Fonction pour utiliser docker-compose ou docker compose
docker_compose_cmd() {
    if command -v docker-compose &> /dev/null; then
        echo "docker-compose"
    else
        echo "docker compose"
    fi
}

# Arrêt des services
stop_services() {
    log_info "Arrêt des services..."

    local compose_cmd=$(docker_compose_cmd)

    case "${1:-}" in
        --remove-volumes)
            log_warning "Arrêt et suppression des volumes (données perdues)..."
            $compose_cmd down -v
            ;;
        --remove-images)
            log_warning "Arrêt et suppression des images..."
            $compose_cmd down --rmi all
            ;;
        --full-clean)
            log_warning "Nettoyage complet (volumes, images, réseau)..."
            $compose_cmd down -v --rmi all --remove-orphans
            ;;
        *)
            $compose_cmd down
            ;;
    esac

    log_success "Services arrêtés"
}

# Affichage de l'aide
show_help() {
    echo "Usage: $0 [options]"
    echo ""
    echo "Options:"
    echo "  (aucune)           Arrêt simple des conteneurs"
    echo "  --remove-volumes   Arrêt + suppression des volumes (⚠️  données perdues)"
    echo "  --remove-images    Arrêt + suppression des images Docker"
    echo "  --full-clean       Nettoyage complet (volumes + images + réseau)"
    echo "  --help             Affiche cette aide"
    echo ""
    echo "Exemples:"
    echo "  $0                 # Arrêt simple"
    echo "  $0 --full-clean    # Nettoyage complet pour redémarrage propre"
}

# Script principal
main() {
    echo -e "${YELLOW}"
    echo "╔══════════════════════════════════════════════════════════════╗"
    echo "║                 ARRÊT CRM INVESTISSEURS                      ║"
    echo "╚══════════════════════════════════════════════════════════════╝"
    echo -e "${NC}"

    cd "$PROJECT_DIR"

    case "${1:-}" in
        --help|help)
            show_help
            exit 0
            ;;
        --remove-volumes|--remove-images|--full-clean)
            echo -e "${RED}⚠️  ATTENTION: Cette opération peut supprimer des données!${NC}"
            echo -n "Êtes-vous sûr ? (y/N): "
            read -r confirmation
            if [[ $confirmation =~ ^[Yy]$ ]]; then
                stop_services "$1"
            else
                log_info "Opération annulée"
                exit 0
            fi
            ;;
        *)
            stop_services "$1"
            ;;
    esac

    echo -e "${GREEN}✅ CRM Investisseurs arrêté${NC}"
    echo -e "${BLUE}Pour redémarrer:${NC} ./scripts/start.sh"
}

main "$@"
