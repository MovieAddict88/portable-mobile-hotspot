package com.example.cornerstonehotspot;

import android.app.admin.DevicePolicyManager;
import android.content.ComponentName;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.os.Bundle;
import android.os.Handler;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import org.json.JSONArray;
import org.json.JSONObject;

import java.io.OutputStream;
import java.net.HttpURLConnection;
import java.net.URL;
import java.util.ArrayList;

public class MainActivity extends AppCompatActivity {

    private DevicePolicyManager dpm;
    private ComponentName adminComponentName;
    private Handler deviceUpdateHandler = new Handler();
    private static final String UPDATE_DEVICES_URL = "http://10.0.2.2/admin/update_devices.php";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        dpm = (DevicePolicyManager) getSystemService(Context.DEVICE_POLICY_SERVICE);
        adminComponentName = new ComponentName(this, MyDeviceAdminReceiver.class);

        Button enableAdminButton = findViewById(R.id.enable_admin_button);
        enableAdminButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                Intent intent = new Intent(DevicePolicyManager.ACTION_ADD_DEVICE_ADMIN);
                intent.putExtra(DevicePolicyManager.EXTRA_DEVICE_ADMIN, adminComponentName);
                intent.putExtra(DevicePolicyManager.EXTRA_ADD_EXPLANATION, "This app needs to be a device administrator to enable Kiosk Mode.");
                startActivity(intent);
            }
        });

        Button enableKioskButton = findViewById(R.id.enable_lockscreen_button);
        enableKioskButton.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if (dpm.isDeviceOwnerApp(getPackageName())) {
                    dpm.setLockTaskPackages(adminComponentName, new String[]{getPackageName()});
                    startLockTask();
                    startActivity(new Intent(MainActivity.this, LockscreenActivity.class));
                    startDeviceUpdates();
                } else {
                    Toast.makeText(MainActivity.this, "This app is not a device owner.", Toast.LENGTH_SHORT).show();
                }
            }
        });
    }

    private void startDeviceUpdates() {
        deviceUpdateHandler.postDelayed(new Runnable() {
            @Override
            public void run() {
                new UpdateDevicesTask().execute();
                deviceUpdateHandler.postDelayed(this, 30000); // 30 seconds
            }
        }, 0);
    }

    private class UpdateDevicesTask extends AsyncTask<Void, Void, Void> {
        @Override
        protected Void doInBackground(Void... voids) {
            ArrayList<String> clients = HotspotManager.getConnectedClients();
            try {
                JSONArray jsonArray = new JSONArray();
                for (String client : clients) {
                    JSONObject jsonObject = new JSONObject();
                    jsonObject.put("ip_address", client);
                    jsonArray.put(jsonObject);
                }

                URL url = new URL(UPDATE_DEVICES_URL);
                HttpURLConnection conn = (HttpURLConnection) url.openConnection();
                conn.setRequestMethod("POST");
                conn.setRequestProperty("Content-Type", "application/json;charset=UTF-8");
                conn.setDoOutput(true);

                OutputStream os = conn.getOutputStream();
                os.write(jsonArray.toString().getBytes("UTF-8"));
                os.close();

                int responseCode = conn.getResponseCode();
                Log.d("MainActivity", "Update devices response code: " + responseCode);

            } catch (Exception e) {
                e.printStackTrace();
            }
            return null;
        }
    }
}