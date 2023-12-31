1. Run `vagrant up`
2. Enter in node1 `vagrant ssh node1`
3. Create a Docker Swarm manager `docker swarm init`
4. Configure node2 and node3 as workers 
5. In node1, run `cd /vagrant; bash /deploy.sh`
6. In local machine, append to `/etc/hosts` the content of `hosts.txt`
7. Open `http://patient.local` for the Patient App
8. Open `http://doctor.local` for the Doctor App
9. Open `http://docker.swarm:8080` for Traefik dashboard
10. Open `http://docker.swarm:9000` for Portainer dashboard
11. Open `http://docker.swarm:3000` for Grafana dashboard using admin/admin