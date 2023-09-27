import time
from locust import HttpUser, task, between

class LocustBot(HttpUser):
    wait_time = between(1, 5)

    @task
    def ws(self):
        headers = {'Host': 'localhost'}
        resp = self.client.get("/", headers=headers)