import http from 'k6/http';

import { sleep } from 'k6';

export const options = {

  thresholds: {
    http_req_failed: ['rate<0.01'], // http errors should be less than 1%
    http_req_duration: ['p(95)<200'], // 95% of requests should be below 200ms
  },
};

export function setup() {
  const url = 'http://127.0.0.1/fusionsuite/backend/v1/token';

  const params = {
    headers: {
      'Content-Type': 'application/json'
    },
  };

  const payload = JSON.stringify({
    login: 'admin',
    password: 'admin',
  });

  const res = http.post(url, payload, params);
  return res.json();
}

export default function (data) {

  const url = 'http://127.0.0.1/fusionsuite/backend/v1/config/types/3';

  const params = {
    headers: {
      'Content-Type': 'application/json',
      'Authorization': 'Bearer '+data.token
    },
  };

  http.get(url, params);

  sleep(1);
}