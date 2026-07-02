<?php
require 'auth.php';

$activeNav = 'mebbook';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MEBbook Admin — Yönetim Paneli</title>
  <link rel="stylesheet" href="admin.css" />
  <style>
    /* ── MEBbook'a özgü ek stiller (admin.css token'larını kullanır) ─── */

    /* Yardımcı */
    .hidden { display: none !important; }

    /* Sekme düğmeleri */
    .mb-tabs { display: flex; gap: 8px; margin-bottom: 20px; flex-wrap: wrap; }
    .mb-tab {
      background: transparent;
      color: var(--muted);
      border: 1px solid var(--border-2);
      border-radius: var(--radius-sm);
      padding: 7px 14px;
      font-family: inherit;
      font-size: .82rem;
      font-weight: 600;
      cursor: pointer;
      transition: color .2s var(--ease), background .2s var(--ease), border-color .2s var(--ease);
      letter-spacing: .01em;
    }
    .mb-tab:hover { color: var(--text); background: var(--border); border-color: var(--border-3); }
    .mb-tab.active {
      background: var(--primary-glow);
      color: var(--primary);
      border-color: var(--primary-border);
    }

    /* Yorum / ban kartları */
    .mb-card {
      background: var(--surface-2);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      padding: 14px 16px;
      margin-bottom: 10px;
      transition: border-color .2s var(--ease);
    }
    .mb-card:hover { border-color: var(--border-2); }

    /* Avatar */
    .mb-avatar {
      width: 34px; height: 34px; border-radius: 50%;
      background: var(--primary-glow);
      color: var(--primary);
      font-weight: 700; font-size: .85rem;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }

    /* Satır yardımcıları */
    .mb-row { display: flex; align-items: center; gap: 10px; }
    .mb-between { justify-content: space-between; }
    .mb-grow { flex: 1; min-width: 0; }
    .mb-text { font-size: .9rem; line-height: 1.5; margin: 8px 0; word-break: break-word; color: var(--text-2); }
    .mb-mono { font-family: 'JetBrains Mono', monospace; font-size: .75rem; color: var(--muted); }
    .mb-muted { color: var(--muted); font-size: .82rem; }
    .mb-field { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
    .mb-field input, .mb-field select { flex: 1; min-width: 160px; }

    /* Şikayet rozeti */
    .mb-pill-danger {
      display: inline-block;
      background: var(--error-bg);
      color: var(--error);
      border: 1px solid var(--error-br);
      border-radius: var(--radius-sm);
      padding: 2px 8px;
      font-size: .72rem;
      font-weight: 700;
    }

    /* İtiraz kutusu */
    .mb-appeal {
      margin-top: 10px; padding: 10px 14px;
      background: rgba(245,158,11,.07);
      border: 1px solid rgba(245,158,11,.2);
      border-radius: var(--radius-sm);
    }
    .mb-pill-warn {
      display: inline-block;
      background: rgba(245,158,11,.12);
      color: var(--warning);
      border-radius: var(--radius-sm);
      padding: 2px 8px;
      font-size: .72rem;
      font-weight: 700;
    }

    /* Chip (seçili kaynak etiketleri) */
    .mb-chip {
      display: inline-flex; align-items: center; gap: 6px;
      background: var(--primary-glow);
      color: var(--primary);
      border: 1px solid var(--primary-border);
      border-radius: 999px;
      padding: 4px 8px 4px 12px;
      font-size: .78rem; font-weight: 600;
    }
    .mb-chip button {
      background: transparent; color: var(--primary); border: none;
      padding: 0 4px; font-size: 16px; line-height: 1; cursor: pointer;
    }

    /* Toast */
    #mb-toast {
      position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%);
      background: var(--surface); color: var(--text);
      border: 1px solid var(--border-2);
      padding: 10px 20px; border-radius: var(--radius);
      font-size: .88rem; font-weight: 500;
      opacity: 0; transition: opacity .2s; pointer-events: none;
      z-index: 9999;
      box-shadow: var(--shadow);
    }
    #mb-toast.show { opacity: 1; }


    .mb-select {
      font-family: inherit;
      font-size: .88rem;
      padding: 9px 12px;
      border: 1px solid var(--border-2);
      border-radius: var(--radius-sm);
      background: var(--bg);
      color: var(--text);
      width: 100%;
      transition: border-color .2s var(--ease), box-shadow .2s var(--ease);
      cursor: pointer;
    }
    .mb-select:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px var(--primary-glow);
    }

    /* input overrides */
    .mb-input {
      font-family: inherit;
      font-size: .88rem;
      padding: 9px 12px;
      border: 1px solid var(--border-2);
      border-radius: var(--radius-sm);
      background: var(--bg);
      color: var(--text);
      width: 100%;
      transition: border-color .2s var(--ease), box-shadow .2s var(--ease);
    }
    .mb-input::placeholder { color: var(--subtle); }
    .mb-input:hover { border-color: var(--border-3); }
    .mb-input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px var(--primary-glow);
    }

    /* Bildirim hedef seçim alanı */
    .mb-source-row { display: flex; flex-direction: column; gap: 10px; }
    .mb-source-chips { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 6px; }

    /* Audience pill */
    .mb-pill-info {
      display: inline-block;
      background: var(--primary-glow);
      color: var(--primary);
      border: 1px solid var(--primary-border);
      border-radius: var(--radius-sm);
      padding: 2px 8px;
      font-size: .72rem; font-weight: 700;
    }
  </style>
</head>
<body>
<div class="admin-wrapper">
  <?php require 'sidebar.php'; ?>

  <div class="admin-content">
    <div class="admin-topbar">
      <span class="admin-topbar-title">MEBbook Yönetimi</span>
      <div class="admin-topbar-actions">
        <span class="mb-muted" style="font-size:.82rem;"><?= htmlspecialchars($_SESSION['admin_username'] ?? '') ?></span>
      </div>
    </div>

    <div class="admin-page" style="max-width:900px;">

      <!-- Firebase bağlantı hatası (varsa) -->
      <div id="mb-error" class="hidden" style="margin-bottom:16px;">
        <div class="alert alert-error" id="mb-errorMsg"></div>
      </div>

      <!-- Panel -->
      <div id="mb-dashboard">
        <div class="admin-page-header" style="margin-bottom:24px;">
          <h1 class="admin-page-title">MEBbook</h1>
          <p class="admin-page-desc">Yorumları, şikayetleri, engelleri ve bildirimleri buradan yönetebilirsiniz.</p>
        </div>

        <div class="mb-tabs">
          <button type="button" class="mb-tab active" data-tab="comments">Yorumlar</button>
          <button type="button" class="mb-tab" data-tab="reports">Şikayetler</button>
          <button type="button" class="mb-tab" data-tab="bans">Engelliler</button>
          <button type="button" class="mb-tab" data-tab="notifs">Bildirimler</button>
          <button type="button" class="mb-tab" data-tab="users">Kullanıcı Ara</button>
          <button type="button" class="mb-tab" data-tab="allusers">Tüm Kullanıcılar</button>
        </div>

        <!-- Yorumlar -->
        <section id="tab-comments">
          <div class="mb-row mb-between" style="margin-bottom:12px;">
            <span class="mb-muted">Son 100 yorum</span>
            <button class="btn btn-outline btn-sm" id="reloadComments">Yenile</button>
          </div>
          <div id="commentsList"></div>
        </section>

        <!-- Şikayetler -->
        <section id="tab-reports" class="hidden">
          <div class="mb-row mb-between" style="margin-bottom:12px;">
            <span class="mb-muted">En çok şikayet alan yorumlar</span>
            <button class="btn btn-outline btn-sm" id="reloadReports">Yenile</button>
          </div>
          <div id="reportsList"></div>
        </section>

        <!-- Engelliler -->
        <section id="tab-bans" class="hidden">
          <div class="form-card" style="margin-bottom:16px;">
            <div class="form-card-title">Kimlik (uid) ile Engelle</div>
            <div class="mb-field">
              <input class="mb-input" id="banUid" placeholder="kullanıcı uid" />
              <input class="mb-input" id="banReason" placeholder="sebep (isteğe bağlı)" />
              <button class="btn btn-danger btn-sm" id="banBtn">Engelle</button>
            </div>
          </div>
          <div class="mb-row mb-between" style="margin-bottom:12px;">
            <span class="mb-muted">Engellenenler</span>
            <button class="btn btn-outline btn-sm" id="reloadBans">Yenile</button>
          </div>
          <div id="bansList"></div>
        </section>

        <!-- Bildirimler -->
        <section id="tab-notifs" class="hidden">
          <div class="form-card" style="margin-bottom:16px;">
            <div class="form-card-title">Bildirim Gönder</div>

            <div class="form-group">
              <label for="notifAudience">Hedef kitle</label>
              <select id="notifAudience" class="mb-select">
                <option value="all">Tüm kullanıcılar</option>
                <option value="user">Tek kullanıcı (uid)</option>
                <option value="source">Bir sayfayı takip edenler</option>
              </select>
            </div>

            <div class="form-group hidden" id="notifUserField">
              <label for="notifUid">Hedef kullanıcı uid</label>
              <input class="mb-input" id="notifUid" placeholder="hedef kullanıcı uid" />
            </div>

            <div id="notifSourceField" class="hidden mb-source-row">
              <div class="form-group" style="margin-bottom:0;">
                <label for="notifKind">Tür</label>
                <select id="notifKind" class="mb-select">
                  <option value="local">İl / İlçe Müdürlüğü</option>
                  <option value="ministry">Bakanlık birimi</option>
                </select>
              </div>
              <div id="notifLocalRow" style="display:flex; gap:8px; flex-wrap:wrap;">
                <select id="notifProvince" class="mb-select" style="flex:1; min-width:140px;">
                  <option value="">İl seçin…</option>
                </select>
                <select id="notifDistrict" class="mb-select" style="flex:1; min-width:140px;">
                  <option value="">İl müdürlüğü</option>
                </select>
              </div>
              <select id="notifMinistry" class="mb-select hidden">
                <option value="">Bakanlık birimi seçin…</option>
              </select>
              <button class="btn btn-outline btn-sm" id="notifAddSource" style="align-self:flex-start;">
                + Hedefe ekle
              </button>
              <div id="notifSelectedSources" class="mb-source-chips">
                <span class="mb-muted">Henüz sayfa seçilmedi.</span>
              </div>
            </div>

            <div class="form-group">
              <label for="notifTitle">Başlık</label>
              <input class="mb-input" id="notifTitle" placeholder="bildirim başlığı" />
            </div>
            <div class="form-group">
              <label for="notifBody">Mesaj</label>
              <input class="mb-input" id="notifBody" placeholder="mesaj metni" />
            </div>
            <div class="form-group">
              <label for="notifLink">Bağlantı <span class="mb-muted">(isteğe bağlı)</span></label>
              <input class="mb-input" id="notifLink" placeholder="https://…" />
            </div>

            <p class="mb-muted" style="margin:4px 0 16px;">
              "Bir sayfayı takip edenler": il/ilçe MEM veya bakanlık birimi seçip
              "Hedefe ekle" ile birden fazla sayfa ekleyebilirsiniz.
            </p>

            <div class="form-actions">
              <button class="btn btn-primary" id="notifSendBtn" style="width:auto;">Gönder</button>
            </div>
          </div>

          <div class="mb-row mb-between" style="margin-bottom:12px;">
            <span class="mb-muted">Gönderilen bildirimler</span>
            <button class="btn btn-outline btn-sm" id="reloadNotifs">Yenile</button>
          </div>
          <div id="notifsList"></div>
        </section>

        <!-- Kullanıcı Ara -->
        <section id="tab-users" class="hidden">
          <div class="form-card" style="margin-bottom:16px;">
            <div class="form-card-title">E-posta veya İsimle Kullanıcı Bul</div>
            <div class="mb-field">
              <input class="mb-input" id="searchInput" placeholder="ornek@gmail.com veya isim" />
              <button class="btn btn-outline btn-sm" id="searchBtn">Ara</button>
            </div>
            <p class="mb-muted" style="margin-top:8px;">
              Apple kullanıcılarında e-posta olmayabilir; isimle de arayabilirsiniz.
            </p>
          </div>
          <div id="usersList"></div>
        </section>

        <!-- Tüm Kullanıcılar -->
        <section id="tab-allusers" class="hidden">
          <div class="form-card" style="margin-bottom:16px;">
            <div class="form-card-title">Tüm Kayıtlı Kullanıcılar</div>
            <div class="mb-row mb-between" style="gap:10px;flex-wrap:wrap;">
              <input class="mb-input" id="allUsersSearch" placeholder="İsim veya e-posta ile filtrele…" style="flex:1;min-width:200px;" />
              <button class="btn btn-outline btn-sm" id="reloadAllUsers">Yenile</button>
            </div>
            <p class="mb-muted" style="margin-top:8px;">
              Tüm kullanıcılar gösterilir; sayfada canlı filtreleme yapabilirsiniz.
            </p>
          </div>
          <div class="mb-row mb-between" style="margin-bottom:12px;">
            <span class="mb-muted" id="allUsersCount">Yükleniyor…</span>
          </div>
          <div id="allUsersList"></div>
          <div style="display:flex;justify-content:center;margin-top:16px;" id="allUsersPagerWrap" class="hidden">
            <button class="btn btn-outline btn-sm" id="allUsersLoadMore">Daha fazla yükle</button>
          </div>
        </section>
      </div><!-- /mb-dashboard -->

    </div><!-- /admin-page -->
  </div><!-- /admin-content -->
</div><!-- /admin-wrapper -->

<div id="mb-toast"></div>

<script type="module">
  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js";
  import {
    getFirestore, collection, query, orderBy, limit, where,
    getDocs, doc, setDoc, addDoc, deleteDoc, serverTimestamp, startAfter
  } from "https://www.gstatic.com/firebasejs/10.12.2/firebase-firestore.js";

  // MEBbook Firebase yapılandırması
  const firebaseConfig = {
    apiKey: "AIzaSyDudWkY-0-t9Ft709OB8ZhxAbtVEYz1FYM",
    authDomain: "mebbook-52d27.firebaseapp.com",
    projectId: "mebbook-52d27",
    storageBucket: "mebbook-52d27.firebasestorage.app",
    messagingSenderId: "677822427703",
    appId: "1:677822427703:web:dcc57327bc27d462c445b2",
  };

  const app = initializeApp(firebaseConfig);
  const db = getFirestore(app);

  // PHP session admin adı (bannedBy / createdBy alanlarında kullanılır)
  const adminUsername = <?= json_encode($_SESSION['admin_username'] ?? 'admin') ?>;

  // ── Yardımcılar ──────────────────────────────────────────────────────────
  const $ = (id) => document.getElementById(id);
  const show = (el) => el.classList.remove("hidden");
  const hide = (el) => el.classList.add("hidden");

  function toast(msg) {
    const t = $("mb-toast");
    t.textContent = msg;
    t.classList.add("show");
    setTimeout(() => t.classList.remove("show"), 2400);
  }

  function esc(s) {
    return String(s ?? "").replace(/[&<>"']/g, (c) => ({
      "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;",
    }[c]));
  }

  function fmtDate(ts) {
    if (!ts) return "";
    const d = ts.toDate ? ts.toDate() : ts;
    try { return d.toLocaleString("tr-TR"); } catch { return ""; }
  }

  function initial(name) {
    const n = (name || "").trim();
    return n ? n[0].toUpperCase() : "?";
  }

  // ── Firebase bağlantısı ───────────────────────────────────────────────────
  // PHP auth.php oturumu zaten doğruluyor; Firebase Auth kullanılmıyor.
  // Firestore'a doğrudan bağlanılıyor — rules "request.auth == null"'a izin vermeli.
  loadComments();
  loadBans();

  // ── Sekmeler ─────────────────────────────────────────────────────────────
  document.querySelectorAll(".mb-tab").forEach((btn) => {
    btn.onclick = () => {
      document.querySelectorAll(".mb-tab").forEach((b) => b.classList.remove("active"));
      btn.classList.add("active");
      for (const name of ["comments", "reports", "bans", "notifs", "users", "allusers"]) {
        $("tab-" + name).classList.toggle("hidden", name !== btn.dataset.tab);
      }
      if (btn.dataset.tab === "reports") loadReports();
      if (btn.dataset.tab === "notifs") loadNotifs();
      if (btn.dataset.tab === "allusers" && allUsersData.length === 0) loadAllUsers();
    };
  });

  // ── Yorumlar ─────────────────────────────────────────────────────────────
  $("reloadComments").onclick = loadComments;

  async function loadComments() {
    const list = $("commentsList");
    list.innerHTML = `<p class="mb-muted">Yükleniyor…</p>`;
    try {
      const q = query(
        collection(db, "comments"),
        orderBy("createdAt", "desc"),
        limit(100),
      );
      const snap = await getDocs(q);
      if (snap.empty) { list.innerHTML = `<p class="mb-muted">Yorum yok.</p>`; return; }
      list.innerHTML = "";
      snap.forEach((d) => list.appendChild(commentCard(d.id, d.data())));
    } catch (e) {
      list.innerHTML = `<p class="mb-muted">Yorumlar yüklenemedi: ${esc(e.message)}</p>`;
    }
  }

  // ── Şikayetler ───────────────────────────────────────────────────────────
  $("reloadReports").onclick = loadReports;

  async function loadReports() {
    const list = $("reportsList");
    list.innerHTML = `<p class="mb-muted">Yükleniyor…</p>`;
    try {
      const q = query(
        collection(db, "comments"),
        where("reportCount", ">", 0),
        orderBy("reportCount", "desc"),
        limit(100),
      );
      const snap = await getDocs(q);
      if (snap.empty) {
        list.innerHTML = `<p class="mb-muted">Şikayet edilen yorum yok.</p>`;
        return;
      }
      list.innerHTML = "";
      snap.forEach((d) => list.appendChild(commentCard(d.id, d.data())));
    } catch (e) {
      list.innerHTML = `<p class="mb-muted">Şikayetler yüklenemedi: ${esc(e.message)}</p>`;
    }
  }

  function commentCard(id, c) {
    const el = document.createElement("div");
    el.className = "mb-card";
    const reports = c.reportCount > 0
      ? `<span class="mb-pill-danger">${c.reportCount} şikayet</span>` : "";
    el.innerHTML = `
      <div class="mb-row">
        <div class="mb-avatar">${esc(initial(c.authorName))}</div>
        <div class="mb-grow">
          <div class="mb-row mb-between">
            <strong style="color:var(--text);font-size:.9rem;">${esc(c.authorName || "Anonim")}</strong>
            ${reports}
          </div>
          <div class="mb-mono">${esc(c.authorUid || "")}</div>
        </div>
      </div>
      <div class="mb-text">${esc(c.text || "")}</div>
      <div class="mb-row mb-between">
        <a href="${esc(c.itemUrl || "#")}" target="_blank" class="mb-muted">Habere git ↗</a>
        <span class="mb-muted">${esc(fmtDate(c.createdAt))}</span>
      </div>
      <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:10px;">
        <button class="btn btn-outline btn-sm banAuthor">Yazarı engelle</button>
        <button class="btn btn-danger btn-sm del">Yorumu sil</button>
      </div>`;
    el.querySelector(".del").onclick = async () => {
      if (!confirm("Bu yorum silinsin mi?")) return;
      try {
        await deleteDoc(doc(db, "comments", id));
        el.remove();
        toast("Yorum silindi.");
      } catch (e) { toast("Silinemedi: " + e.message); }
    };
    el.querySelector(".banAuthor").onclick = () => {
      const reason = prompt("Engelleme sebebi (isteğe bağlı):", "uygunsuz yorum");
      if (reason === null) return;
      banUser(c.authorUid, reason);
    };
    return el;
  }

  // ── Engelleme ────────────────────────────────────────────────────────────
  $("banBtn").onclick = () => {
    const uid = $("banUid").value.trim();
    if (!uid) { toast("uid girin."); return; }
    banUser(uid, $("banReason").value.trim());
  };
  $("reloadBans").onclick = loadBans;

  async function banUser(uid, reason) {
    if (!uid) { toast("Geçersiz uid."); return; }
    try {
      await setDoc(doc(db, "bannedUsers", uid), {
        reason: reason || "",
        bannedAt: serverTimestamp(),
        bannedBy: adminUsername,
      });
      toast("Kullanıcı engellendi.");
      $("banUid").value = ""; $("banReason").value = "";
      loadBans();
    } catch (e) { toast("Engellenemedi: " + e.message); }
  }

  async function unbanUser(uid) {
    try {
      await deleteDoc(doc(db, "bannedUsers", uid));
      try { await deleteDoc(doc(db, "banAppeals", uid)); } catch (_) {}
      toast("Engel kaldırıldı.");
      loadBans();
    } catch (e) { toast("İşlem başarısız: " + e.message); }
  }

  async function loadBans() {
    const list = $("bansList");
    list.innerHTML = `<p class="mb-muted">Yükleniyor…</p>`;
    try {
      const [bansSnap, appealsSnap] = await Promise.all([
        getDocs(collection(db, "bannedUsers")),
        getDocs(collection(db, "banAppeals")),
      ]);
      const appeals = new Map();
      appealsSnap.forEach((d) => appeals.set(d.id, d.data()));

      if (bansSnap.empty) { list.innerHTML = `<p class="mb-muted">Engelli kullanıcı yok.</p>`; return; }
      list.innerHTML = "";
      bansSnap.forEach((d) => {
        const b = d.data();
        const appeal = appeals.get(d.id);
        const el = document.createElement("div");
        el.className = "mb-card";
        const appealHtml = appeal
          ? `<div class="mb-appeal">
               <div class="mb-row" style="gap:6px;">
                 <span class="mb-pill-warn">İtiraz</span>
                 <span class="mb-muted">${esc(fmtDate(appeal.createdAt))}</span>
               </div>
               <div class="mb-text" style="margin:6px 0 0;">${esc(appeal.message || "")}</div>
             </div>`
          : `<div class="mb-muted" style="margin-top:8px;">Talep yok.</div>`;
        el.innerHTML = `
          <div class="mb-row mb-between">
            <div class="mb-grow">
              <div class="mb-mono">${esc(d.id)}</div>
              <div class="mb-muted">${esc(b.reason || "sebep belirtilmedi")}</div>
              <div class="mb-muted">${esc(fmtDate(b.bannedAt))}</div>
            </div>
            <button class="btn btn-outline btn-sm unban">Engeli kaldır</button>
          </div>
          ${appealHtml}`;
        el.querySelector(".unban").onclick = () => unbanUser(d.id);
        list.appendChild(el);
      });
    } catch (e) {
      list.innerHTML = `<p class="mb-muted">Yüklenemedi: ${esc(e.message)}</p>`;
    }
  }

  // ── Bildirimler ──────────────────────────────────────────────────────────
  const notifAudience = $("notifAudience");
  notifAudience.onchange = () => {
    const v = notifAudience.value;
    $("notifUserField").classList.toggle("hidden", v !== "user");
    $("notifSourceField").classList.toggle("hidden", v !== "source");
    if (v === "source") loadStructuredCatalog();
  };
  $("notifSendBtn").onclick = sendNotif;
  $("reloadNotifs").onclick = loadNotifs;

  let structuredCatalog = null;
  const selectedSources = new Map();

  async function loadStructuredCatalog() {
    if (structuredCatalog) return;
    try {
      const res = await fetch("sources_structured.json");
      structuredCatalog = await res.json();
      fillProvinceOptions();
      fillMinistryOptions();
    } catch (e) {
      toast("Sayfa kataloğu yüklenemedi: " + e.message);
    }
  }

  function fillProvinceOptions() {
    const sel = $("notifProvince");
    sel.innerHTML = `<option value="">İl seçin…</option>`;
    for (const p of structuredCatalog.provinces) {
      const opt = document.createElement("option");
      opt.value = p.id;
      opt.textContent = p.name;
      sel.appendChild(opt);
    }
  }

  function fillMinistryOptions() {
    const sel = $("notifMinistry");
    sel.innerHTML = `<option value="">Bakanlık birimi seçin…</option>`;
    for (const u of structuredCatalog.ministryUnits) {
      const opt = document.createElement("option");
      opt.value = u.id;
      opt.textContent = u.name;
      sel.appendChild(opt);
    }
  }

  $("notifKind").onchange = () => {
    const isLocal = $("notifKind").value === "local";
    $("notifLocalRow").classList.toggle("hidden", !isLocal);
    $("notifMinistry").classList.toggle("hidden", isLocal);
  };

  $("notifProvince").onchange = () => {
    const provId = $("notifProvince").value;
    const dsel = $("notifDistrict");
    dsel.innerHTML = `<option value="">İl müdürlüğü</option>`;
    if (!provId || !structuredCatalog) return;
    const prov = structuredCatalog.provinces.find((p) => p.id === provId);
    if (!prov) return;
    for (const d of prov.districts) {
      const opt = document.createElement("option");
      opt.value = d.id;
      opt.textContent = d.name;
      dsel.appendChild(opt);
    }
  };

  $("notifAddSource").onclick = () => {
    if (!structuredCatalog) { toast("Katalog yükleniyor…"); return; }
    let id = "", name = "";
    if ($("notifKind").value === "ministry") {
      const sel = $("notifMinistry");
      id = sel.value;
      name = sel.options[sel.selectedIndex]?.textContent || "";
      if (!id) { toast("Bir bakanlık birimi seçin."); return; }
    } else {
      const prov = structuredCatalog.provinces.find(
        (p) => p.id === $("notifProvince").value);
      if (!prov) { toast("Bir il seçin."); return; }
      const distId = $("notifDistrict").value;
      if (distId) {
        const d = prov.districts.find((x) => x.id === distId);
        id = d.id; name = `${prov.name} / ${d.name} MEB`;
      } else {
        id = prov.id; name = `${prov.name} MEB`;
      }
    }
    if (selectedSources.has(id)) { toast("Bu sayfa zaten ekli."); return; }
    selectedSources.set(id, name);
    renderSelectedSources();
  };

  function renderSelectedSources() {
    const box = $("notifSelectedSources");
    box.innerHTML = "";
    if (selectedSources.size === 0) {
      box.innerHTML = `<span class="mb-muted">Henüz sayfa seçilmedi.</span>`;
      return;
    }
    for (const [id, name] of selectedSources) {
      const chip = document.createElement("span");
      chip.className = "mb-chip";
      chip.innerHTML = `<span>${esc(name)}</span><button title="kaldır">×</button>`;
      chip.querySelector("button").onclick = () => {
        selectedSources.delete(id);
        renderSelectedSources();
      };
      box.appendChild(chip);
    }
  }

  async function sendNotif() {
    const audience = notifAudience.value;
    const title = $("notifTitle").value.trim();
    const body = $("notifBody").value.trim();
    const link = $("notifLink").value.trim();
    if (!title && !body) { toast("Başlık veya mesaj girin."); return; }

    const payload = {
      audience, title, body,
      createdAt: serverTimestamp(),
      createdBy: adminUsername,
    };
    if (link) payload.linkUrl = link;

    if (audience === "user") {
      const uid = $("notifUid").value.trim();
      if (!uid) { toast("Hedef uid girin."); return; }
      payload.targetUid = uid;
    } else if (audience === "source") {
      if (selectedSources.size === 0) { toast("En az bir sayfa ekleyin."); return; }
      payload.targetSourceIds = [...selectedSources.keys()];
      payload.targetSourceNames = [...selectedSources.values()];
    }

    try {
      await addDoc(collection(db, "adminNotifications"), payload);
      toast("Bildirim gönderildi.");
      $("notifTitle").value = ""; $("notifBody").value = ""; $("notifLink").value = "";
      selectedSources.clear();
      renderSelectedSources();
      loadNotifs();
    } catch (e) { toast("Gönderilemedi: " + e.message); }
  }

  async function loadNotifs() {
    const list = $("notifsList");
    list.innerHTML = `<p class="mb-muted">Yükleniyor…</p>`;
    try {
      const q = query(
        collection(db, "adminNotifications"),
        orderBy("createdAt", "desc"),
        limit(100),
      );
      const snap = await getDocs(q);
      if (snap.empty) {
        list.innerHTML = `<p class="mb-muted">Henüz bildirim gönderilmedi.</p>`;
        return;
      }
      list.innerHTML = "";
      snap.forEach((d) => list.appendChild(notifCard(d.id, d.data())));
    } catch (e) {
      list.innerHTML = `<p class="mb-muted">Yüklenemedi: ${esc(e.message)}</p>`;
    }
  }

  function audienceLabel(n) {
    if (n.audience === "all") return "Tüm kullanıcılar";
    if (n.audience === "user") return `Tek kullanıcı: ${esc(n.targetUid || "")}`;
    if (n.audience === "source") {
      const names = Array.isArray(n.targetSourceNames) && n.targetSourceNames.length
        ? n.targetSourceNames
        : (Array.isArray(n.targetSourceIds) && n.targetSourceIds.length
            ? n.targetSourceIds
            : [n.targetSourceName || n.targetSourceId || ""]);
      return `Sayfa takipçileri: ${esc(names.filter(Boolean).join(", "))}`;
    }
    return esc(n.audience || "");
  }

  function notifCard(id, n) {
    const el = document.createElement("div");
    el.className = "mb-card";
    el.innerHTML = `
      <div class="mb-row mb-between">
        <span class="mb-pill-info">${audienceLabel(n)}</span>
        <span class="mb-muted">${esc(fmtDate(n.createdAt))}</span>
      </div>
      <div class="mb-text" style="margin:8px 0 2px;"><strong style="color:var(--text);">${esc(n.title || "")}</strong></div>
      <div class="mb-text" style="margin:0;">${esc(n.body || "")}</div>
      ${n.linkUrl ? `<a href="${esc(n.linkUrl)}" target="_blank" class="mb-muted">Bağlantı ↗</a>` : ""}
      <div style="display:flex;justify-content:flex-end;margin-top:10px;">
        <button class="btn btn-danger btn-sm delNotif">Sil</button>
      </div>`;
    el.querySelector(".delNotif").onclick = async () => {
      if (!confirm("Bu bildirim silinsin mi?")) return;
      try {
        await deleteDoc(doc(db, "adminNotifications", id));
        el.remove();
        toast("Bildirim silindi.");
      } catch (e) { toast("Silinemedi: " + e.message); }
    };
    return el;
  }

  // ── Kullanıcı arama ──────────────────────────────────────────────────────
  $("searchBtn").onclick = searchUsers;
  $("searchInput").addEventListener("keydown", (e) => {
    if (e.key === "Enter") searchUsers();
  });

  async function searchUsers() {
    const term = $("searchInput").value.trim();
    const list = $("usersList");
    if (!term) { toast("Arama terimi girin."); return; }
    list.innerHTML = `<p class="mb-muted">Aranıyor…</p>`;
    try {
      const found = new Map();
      for (const field of ["email", "displayName"]) {
        const q = query(collection(db, "users"), where(field, "==", term), limit(20));
        const snap = await getDocs(q);
        snap.forEach((d) => found.set(d.id, d.data()));
      }
      if (found.size === 0) {
        list.innerHTML = `<p class="mb-muted">Eşleşen kullanıcı bulunamadı.
          (Tam e-posta veya tam isim gerekir.)</p>`;
        return;
      }
      list.innerHTML = "";
      for (const [uid, u] of found) {
        const el = document.createElement("div");
        el.className = "mb-card";
        el.innerHTML = `
          <div class="mb-row mb-between">
            <div class="mb-grow">
              <strong style="color:var(--text);font-size:.9rem;">${esc(u.displayName || "Anonim")}</strong>
              <div class="mb-muted">${esc(u.email || "e-posta yok")}</div>
              <div class="mb-mono">${esc(uid)}</div>
            </div>
            <button class="btn btn-danger btn-sm banUser">Engelle</button>
          </div>`;
        el.querySelector(".banUser").onclick = () => {
          const reason = prompt("Engelleme sebebi (isteğe bağlı):", "");
          if (reason === null) return;
          banUser(uid, reason);
        };
        list.appendChild(el);
      }
    } catch (e) {
      list.innerHTML = `<p class="mb-muted">Arama başarısız: ${esc(e.message)}</p>`;
    }
  }

  // ── Tüm Kullanıcılar ─────────────────────────────────────────────────────
  let allUsersData = [];       // tüm çekilen kayıtlar
  let allUsersCursor = null;   // sayfalama imleci
  const ALL_USERS_PAGE = 200;  // sayfa başı kayıt (orderBy olmadan büyük tutulabilir)

  $("reloadAllUsers").onclick = () => { allUsersData = []; allUsersCursor = null; loadAllUsers(); };
  $("allUsersLoadMore").onclick = () => loadAllUsers(true);
  $("allUsersSearch").addEventListener("input", renderAllUsers);

  async function loadAllUsers(append = false) {
    const list = $("allUsersList");
    const countEl = $("allUsersCount");
    if (!append) {
      list.innerHTML = `<p class="mb-muted">Yükleniyor…</p>`;
      countEl.textContent = "Yükleniyor…";
      allUsersData = [];
      allUsersCursor = null;
    }
    try {
      // orderBy kullanmıyoruz — createdAt alanı olmayan kayıtlarda sorgu boş döner.
      // Bunun yerine düz koleksiyon taraması yapıp client tarafında sıralıyoruz.
      let q;
      if (append && allUsersCursor) {
        q = query(
          collection(db, "users"),
          startAfter(allUsersCursor),
          limit(ALL_USERS_PAGE)
        );
      } else {
        q = query(
          collection(db, "users"),
          limit(ALL_USERS_PAGE)
        );
      }
      const snap = await getDocs(q);

      if (!snap.empty) {
        allUsersCursor = snap.docs[snap.docs.length - 1];
        snap.forEach((d) => allUsersData.push({ uid: d.id, ...d.data() }));
      }

      // Client tarafında createdAt'a göre sırala (alan yoksa sona at)
      allUsersData.sort((a, b) => {
        const ta = a.createdAt?.toMillis?.() ?? 0;
        const tb = b.createdAt?.toMillis?.() ?? 0;
        return tb - ta;
      });

      // "Daha fazla" butonu: sayfa dolu ise göster
      const pagerWrap = $("allUsersPagerWrap");
      if (snap.size === ALL_USERS_PAGE) {
        pagerWrap.classList.remove("hidden");
      } else {
        pagerWrap.classList.add("hidden");
      }
      renderAllUsers();
    } catch (e) {
      console.error("loadAllUsers hatası:", e);
      $("allUsersList").innerHTML = `
        <div class="mb-card" style="border-color:var(--error-br);">
          <div style="color:var(--error);font-weight:600;margin-bottom:6px;">⚠️ Kullanıcılar yüklenemedi</div>
          <div class="mb-mono">${esc(e.code || "")} — ${esc(e.message)}</div>
          <div class="mb-muted" style="margin-top:8px;">
            Olası nedenler:<br>
            • <code>users</code> koleksiyonu mevcut değil veya adı farklı<br>
            • Firestore kuralları bu koleksiyona izin vermiyor<br>
            • Firestore index gerekiyor (konsol linkine bakın)
          </div>
        </div>`;
      $("allUsersCount").textContent = "Hata";
    }
  }

  function renderAllUsers() {
    const term = $("allUsersSearch").value.trim().toLowerCase();
    const filtered = term
      ? allUsersData.filter((u) =>
          (u.displayName || "").toLowerCase().includes(term) ||
          (u.email || "").toLowerCase().includes(term)
        )
      : allUsersData;

    $("allUsersCount").textContent = `${filtered.length} / ${allUsersData.length} kullanıcı`;

    const list = $("allUsersList");
    if (filtered.length === 0) {
      list.innerHTML = `<p class="mb-muted">Kullanıcı bulunamadı.</p>`;
      return;
    }
    list.innerHTML = "";
    for (const u of filtered) {
      list.appendChild(allUserCard(u));
    }
  }

  function allUserCard(u) {
    const el = document.createElement("div");
    el.className = "mb-card";
    const joinDate = u.createdAt ? fmtDate(u.createdAt) : "—";
    const commentCount = u.commentCount != null ? u.commentCount : "—";
    el.innerHTML = `
      <div class="mb-row mb-between">
        <div class="mb-row" style="gap:12px;flex:1;min-width:0;">
          <div class="mb-avatar" style="width:40px;height:40px;font-size:.95rem;flex-shrink:0;">${esc(initial(u.displayName))}</div>
          <div class="mb-grow">
            <div class="mb-row" style="gap:8px;flex-wrap:wrap;align-items:baseline;">
              <strong style="color:var(--text);font-size:.9rem;">${esc(u.displayName || "Anonim")}</strong>
              ${u.email ? `<span class="mb-muted" style="font-size:.8rem;">${esc(u.email)}</span>` : `<span class="mb-muted" style="font-size:.8rem;">e-posta yok</span>`}
            </div>
            <div class="mb-mono" style="margin-top:2px;">${esc(u.uid)}</div>
            <div class="mb-row" style="gap:16px;margin-top:4px;flex-wrap:wrap;">
              <span class="mb-muted" title="Kayıt tarihi">📅 ${esc(joinDate)}</span>
              <span class="mb-muted" title="Yorum sayısı">💬 ${esc(String(commentCount))}</span>
              ${u.provider ? `<span class="mb-muted" title="Giriş yöntemi">🔑 ${esc(u.provider)}</span>` : ""}
            </div>
          </div>
        </div>
        <button class="btn btn-danger btn-sm banAllUser" style="flex-shrink:0;">Engelle</button>
      </div>`;
    el.querySelector(".banAllUser").onclick = () => {
      const reason = prompt("Engelleme sebebi (isteğe bağlı):", "");
      if (reason === null) return;
      banUser(u.uid, reason);
    };
    return el;
  }
</script>
</body>
</html>
