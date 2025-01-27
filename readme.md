# Motor Town Server Management Web App

A web-based tool for managing your **Motor Town** game server. This app allows you to:
- Monitor and manage **online players** in real-time.
- View and manage the **banned player list**.
- Perform actions like **kicking, banning**, and **unbanning** players directly from the dashboard.

![online player list](https://github.com/BjornB2/MotorTown-Server-Manager-API-WebApp/blob/main/screenshots/online.png)

---

## 🚀 Features

- **Real-time Player Monitoring**
  - Displays a list of online players.
  - Buttons to kick or ban players.

- **Banned Player Management**
  - View a list of banned players.
  - Unban players with a single click.

- **Debug Mode**
  - Toggle a debug box to see raw API requests and responses.

- **Session Management**
  - Choose to stay logged in via persistent cookies.

---

## ⚙️ Requirements

To use this web app, you need:

- **Motor Town Server** with the Web API enabled:
  - Set `bEnableHostWebAPIServer` to `true` in your `DedicatedServerConfig.json`.
  - Configure a `HostWebAPIServerPassword` and ensure the Web API port is accessible (default: `8080`).

- **PHP**:
  - This app is built in PHP and requires a web server capable of running PHP (e.g., Apache, Nginx, or similar).

---

## 🛠️ Setting Up a Web Server

This app requires a PHP-supported web server. Some examples include:  

- **PHP Built-in Development Server**
- **XAMPP** or **WAMP** (Windows)
- **Synology** or **QNAP NAS Web Server**

It is recommended to run the app locally or on a private network to avoid mixed content issues (e.g., HTTP API calls blocked on an HTTPS site).  

**Note**: The developer does NOT provide support for setting up your web server.

---

## 🔒 Security Considerations

- Keep your **Motor Town API password** secure. Do not share it or expose it in a public environment.
- Always run this application on a trusted device or internal/private network.

---

## ❗ Disclaimer

This project is provided "as-is" without any guarantees. The developer does **NOT** provide support for:
- Setting up your web server.
- Troubleshooting PHP or server-related issues.

Use this app at your own discretion.

---

## 📧 Support

If you encounter bugs or issues with the app itself (not related to your server setup), feel free to open an issue on GitHub.
