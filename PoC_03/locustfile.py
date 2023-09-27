import time
from locust import HttpUser, task, between

class LocustBot(HttpUser):
    wait_time = between(1, 5)

    def on_start(self):
        self.cookie = ""

    @task
    def ws(self):
        headers = {'Host': 'localhost', "Cookie": self.cookie}
        resp = self.client.get("/", headers=headers)

        self.cookie = f"routing_cookie={resp.cookies.get('routing_cookie')}"