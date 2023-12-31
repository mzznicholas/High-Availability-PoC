docker build -t pgapache .

docker-compose --profile base down
docker-compose --profile base up -d 