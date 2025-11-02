#!/bin/bash

# Quick WebSocket Connection Test Script

echo "Testing Reverb WebSocket connection..."
echo ""

# Check if Reverb is running
echo "1. Checking Reverb process..."
if pgrep -f "reverb:start" > /dev/null; then
    echo "✅ Reverb process found"
else
    echo "❌ Reverb process NOT running"
    exit 1
fi

# Check if port 8080 is listening
echo "2. Checking port 8080..."
if netstat -tln | grep :8080 > /dev/null; then
    echo "✅ Port 8080 is listening"
else
    echo "❌ Port 8080 NOT listening"
    exit 1
fi

# Check Supervisor status
echo "3. Checking Supervisor status..."
supervisorctl status laravel-reverb

# Check recent logs
echo ""
echo "4. Recent Reverb logs:"
tail -n 20 /var/www/laravel/storage/logs/reverb.log

echo ""
echo "5. Test complete!"
echo ""
echo "Next steps:"
echo "1. Open browser console on https://dreamyschoolph.site"
echo "2. Run: console.log(window.Echo)"
echo "3. Check connection state: Echo.connector.pusher.connection.state"
echo "4. Should show 'connected' or 'connecting'"

