# Fix Nginx WebSocket Proxy for Reverb

Your Nginx config should look like this:

```nginx
location /app/ {
    proxy_pass http://127.0.0.1:8080;
    proxy_http_version 1.1;
    proxy_set_header Upgrade $http_upgrade;
    proxy_set_header Connection "upgrade";
    proxy_set_header Host $host;
    proxy_set_header X-Real-IP $remote_addr;
    proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
    proxy_set_header X-Forwarded-Proto $scheme;
    proxy_read_timeout 60s;
    proxy_send_timeout 60s;
    proxy_connect_timeout 60s;
    
    # Important for WebSocket
    proxy_buffering off;
}
```

The key points:
1. `proxy_pass` points to `127.0.0.1:8080` (where Reverb runs)
2. `Upgrade` and `Connection` headers are set
3. `proxy_buffering off` prevents issues with WebSocket streams

After updating, reload Nginx:
```bash
sudo nginx -t  # Test config
sudo systemctl reload nginx
```

Then test with full path (include app key):
```bash
curl -i -N \
  -H "Connection: Upgrade" \
  -H "Upgrade: websocket" \
  -H "Sec-WebSocket-Version: 13" \
  -H "Sec-WebSocket-Key: test" \
  https://dreamyschoolph.site:443/app/ysobotsfpebn3bo0sqzl
```


