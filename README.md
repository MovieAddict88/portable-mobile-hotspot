# Cornerstone Portable Mobile Hotspot

This application turns an Android device into a dedicated, password-protected mobile hotspot. It uses Android's Kiosk Mode (Lock Task Mode) to prevent users from navigating away from the app or changing settings.

## Setup

To use this application, you must set it as the device owner. This can only be done on a new or factory-reset device via ADB.

1.  **Install the app:** `adb install com.example.cornerstonehotspot.apk`
2.  **Set as device owner:** `adb shell dpm set-device-owner com.example.cornerstonehotspot/.MyDeviceAdminReceiver`

Once the app is set as the device owner, you can open it and tap "Enable Kiosk Mode" to lock the device. The password to unlock the device is managed by a separate admin panel.

## Password Generation

To enable automatic password generation, you need to set up a cron job to run the `generate_password.php` script. For example, to run the script every hour, add the following line to your crontab:

```
* * * * * php /path/to/your/project/admin/generate_password.php
```

## Device Monitoring

The admin panel includes a feature to monitor connected devices. To enable this, you need to set up a cron job to run the `scan_devices.php` script. This script requires a network scanning tool like `arp-scan` to be installed on the server.

```
* * * * * php /path/to/your/project/admin/scan_devices.php
```
