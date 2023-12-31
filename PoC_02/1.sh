docker-compose --profile base up -d 
docker-compose --profile ws1 up -d

sleep 3
python3 -m webbrowser http://localhost:9089/