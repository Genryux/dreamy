# Mobile App WebSocket Connection Test

The mobile app needs to connect to:
```
wss://dreamyschoolph.site:443/app/ysobotsfpebn3bo0sqzl
```

This should be proxied by Nginx to `http://127.0.0.1:8080/app/...`

## Test WebSocket Connection

Run this on your server to test if WebSocket upgrade works:

```bash
# Test WebSocket connection with proper upgrade headers
curl -i -N \
  -H "Connection: Upgrade" \
  -H "Upgrade: websocket" \
  -H "Sec-WebSocket-Version: 13" \
  -H "Sec-WebSocket-Key: test" \
  https://dreamyschoolph.site:443/app/
```

If this fails, Nginx isn't proxying WebSocket properly.

## Mobile App Debugging

In your mobile app, check these:

1. **Connection Status**: Look for console logs like:
   - `✅ Reverb connected successfully`
   - `❌ Reverb connection error:`

2. **Channel Subscription**: Check if you see:
   - `✅ Successfully subscribed to public students channel`
   - `✅ Successfully subscribed to private user channel`

3. **Auth Endpoint**: Verify `/api/broadcasting/auth` is accessible and returns correct auth tokens for private channels

## Possible Issues

1. **Nginx WebSocket Proxy**: Make sure the `/app/` location in Nginx has proper WebSocket headers
2. **Auth Token**: Mobile app needs valid Bearer token for private channel auth
3. **CORS**: Make sure CORS allows mobile app origin


