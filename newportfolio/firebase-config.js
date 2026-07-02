import { initializeApp } from "https://www.gstatic.com/firebasejs/10.13.0/firebase-app.js";
import { getFirestore } from "https://www.gstatic.com/firebasejs/10.13.0/firebase-firestore.js";

const firebaseConfig = {
  apiKey: "AIzaSyA_x7mvSpQoVi_Tzq-1zDi-7UJywHO3Cao",
  authDomain: "wepapp-bc9da.firebaseapp.com",
  projectId: "wepapp-bc9da",
  storageBucket: "wepapp-bc9da.firebasestorage.app",
  messagingSenderId: "438291314799",
  appId: "1:438291314799:web:ee1355eb25aef30431359a",
  measurementId: "G-LM28DN70M9"
};
const app = initializeApp(firebaseConfig);
const db = getFirestore(app);

export { db };
