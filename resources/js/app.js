
import "./bootstrap";
import "./fcm";
import Alpine from "alpinejs";
import Collapse from "@alpinejs/collapse"; // ✅ Tambahkan ini

window.Alpine = Alpine;

// ✅ Daftarkan plugin di sini
Alpine.plugin(Collapse);

Alpine.start();
