package com.example.cornerstonehotspot;

import java.io.BufferedReader;
import java.io.FileReader;
import java.util.ArrayList;

public class HotspotManager {

    public static ArrayList<String> getConnectedClients() {
        ArrayList<String> clients = new ArrayList<>();
        try {
            BufferedReader br = new BufferedReader(new FileReader("/proc/net/arp"));
            String line;
            while ((line = br.readLine()) != null) {
                String[] splitted = line.split(" +");
                if (splitted != null && splitted.length >= 4) {
                    String mac = splitted[3];
                    if (mac.matches("..:..:..:..:..:..")) {
                        clients.add(splitted[0]); // IP Address
                    }
                }
            }
        } catch (Exception e) {
            e.printStackTrace();
        }
        return clients;
    }
}
