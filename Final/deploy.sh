docker network create --driver=overlay traefik-public

docker stack deploy -c traefik-stack.yml traefik
docker stack deploy -c portainer-agent-stack.yml portainer
docker stack deploy -c app-stack.yml app