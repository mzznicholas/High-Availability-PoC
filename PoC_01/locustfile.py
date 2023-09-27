import time
from locust import HttpUser, task, between

class LocustBot(HttpUser):
    wait_time = between(1, 5)

    @task
    def root(self):
        self.client.get("/")