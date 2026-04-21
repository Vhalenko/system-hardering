import { defineStore } from "pinia";
import { ref, computed } from "vue";
import api from "../services/api";

export const useAuthStore = defineStore("auth", () => {
  const user = ref(JSON.parse(localStorage.getItem("user") || "null"));

  const isLoggedIn = computed(() => !!user.value);
  const role = computed(() => user.value?.role || null);
  const isSuperAdmin = computed(() => role.value === "super_admin");
  const isAdmin = computed(
    () => role.value === "admin" || role.value === "super_admin",
  );
  const isFriend = computed(() => !!user.value);

  async function login(email, password) {
    const data = await api.post("/login", { email, password });
    user.value = data.user;
    localStorage.setItem("user", JSON.stringify(data.user));
    localStorage.setItem("token", data.token);
    return data.user;
  }

  function logout() {
    user.value = null;
    localStorage.removeItem("user");
    localStorage.removeItem("token");
  }

  return {
    user,
    isLoggedIn,
    role,
    isSuperAdmin,
    isAdmin,
    isFriend,
    login,
    logout,
  };
});
