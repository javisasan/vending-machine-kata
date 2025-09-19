echo "**** Stopping containers..."
docker stop $(docker ps -aq)
echo "**** Removing containers..."
docker rm $(docker ps -aq)
echo "**** Removing images..."
docker rmi $(docker images -aq)
echo "**** Removing volumes..."
docker volume rm $(docker volume ls)
echo "**** Removing network..."
docker network rm pfdev
