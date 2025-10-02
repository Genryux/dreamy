# 🧪 Private Notifications Testing Guide

## ✅ Ready to Test!

Your private notification system is now complete and ready for testing.

## 🚀 Quick Test Steps

### **1. Test Private Notification to Specific User**

```bash
# Replace with your actual domain and user ID
curl -X POST http://localhost:8888/test-private-notification \
  -H "Content-Type: application/json" \
  -d '{
    "user_id": 1,
    "title": "Private Test Notification",
    "message": "This is a private notification sent only to you!",
    "type": "both",
    "url": "/student/dashboard"
  }'
```

### **2. Test Invoice Reminder**

```bash
curl -X POST http://localhost:8888/test-invoice-reminder \
  -H "Content-Type: application/json" \
  -d '{
    "student_id": 1,
    "invoice_number": "INV-2024-001",
    "amount": 5000.00
  }'
```

### **3. Test Enrollment Confirmation**

```bash
curl -X POST http://localhost:8888/test-enrollment-confirmation \
  -H "Content-Type: application/json" \
  -d '{
    "student_id": 1,
    "section": "Grade 10 - Einstein",
    "academic_year": "2024-2025"
  }'
```

### **4. Test Grade Notification**

```bash
curl -X POST http://localhost:8888/test-grade-notification \
  -H "Content-Type: application/json" \
  -d '{
    "student_id": 1,
    "subject": "Mathematics",
    "grade": "95",
    "quarter": "First Quarter"
  }'
```

## 📱 Mobile App Testing

### **What to Expect:**

1. **Login to mobile app** with user ID 1
2. **Check logs** - you should see:
   ```
   ✅ Retrieved user ID for private channel: 1
   📱 Subscribing to private channel: private.App.Models.User.1
   ✅ Successfully subscribed to private user channel
   ```

3. **Send test notification** using curl commands above
4. **Mobile app should receive** notification instantly:
   ```
   📱 Private notification received via WebSocket: {...}
   ```

5. **Check notifications screen** - notification should appear in the list
6. **Check badge** - unread count should update

## 🔍 Debug Steps

### **If Private Notifications Don't Work:**

1. **Check Laravel Reverb is running:**
   ```bash
   php artisan reverb:start
   ```

2. **Check mobile app logs for connection:**
   ```
   ✅ Reverb connected successfully
   ✅ Successfully subscribed to public students channel
   ✅ Successfully subscribed to private user channel
   ```

3. **Verify user ID is retrieved:**
   ```
   ✅ Retrieved user ID for private channel: {user_id}
   ```

4. **Test public notifications still work:**
   ```bash
   # This should still work for all students
   curl -X POST http://localhost:8888/test-notification
   ```

## 📊 Expected Behavior

### **Private Notifications:**
- ✅ Only the specific user receives the notification
- ✅ Other users don't see it
- ✅ Appears in notifications screen
- ✅ Updates badge count
- ✅ Can be marked as read

### **Public Notifications:**
- ✅ All students receive the notification
- ✅ Appears in all students' notification screens
- ✅ Works exactly as before

## 🎯 Success Criteria

✅ **Private notifications** reach only the intended user  
✅ **Public notifications** still work for all users  
✅ **Mobile app** receives both types seamlessly  
✅ **Badge counts** update correctly  
✅ **Notifications screen** shows all notifications  
✅ **Mark as read** works for both types  

## 🔧 Troubleshooting

### **Issue: "No auth token found"**
- **Solution**: Make sure user is logged in to mobile app

### **Issue: "Failed to subscribe to private user channel"**
- **Solution**: Check that user ID is being retrieved correctly

### **Issue: "Private notifications not received"**
- **Solution**: Verify Laravel Reverb is running and channels.php is correct

### **Issue: "Public notifications stopped working"**
- **Solution**: Check that public channel subscription is still active

## 🎉 Ready to Go!

Your notification system now supports:
- **Public announcements** → All students
- **Private notifications** → Individual users
- **Real-time delivery** → Instant via WebSocket
- **Persistent storage** → Database for important notifications
- **Mobile integration** → Seamless React Native support

Start sending private notifications and watch them appear instantly in your mobile app! 🚀
