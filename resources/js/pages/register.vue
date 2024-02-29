<template>
    <v-container class="mt-2">
        <v-row justify="center">
            <v-col cols="12" sm="8" md="6">
                <v-card>
                    <v-card-title>
                        <h2>Register</h2>
                        <v-divider></v-divider>
                    </v-card-title>

                    <v-card-text>
                        <v-form @submit.prevent="register">
                            <v-text-field
                                v-model="user.name"
                                label="Name"
                                :rules="nameRules"
                                outlined
                            ></v-text-field>

                            <v-text-field
                                v-model="user.email"
                                label="Email"
                                :rules="emailRules"
                                type="email"
                                outlined
                            ></v-text-field>

                            <v-text-field
                                v-model="user.password"
                                label="Password"
                                :rules="passwordRules"
                                type="password"
                                outlined
                            ></v-text-field>

                            <v-text-field
                                v-model="user.confirmpassword"
                                label="Confirm Password"
                                :rules="confirmPasswordRules"
                                type="password"
                                outlined
                            ></v-text-field>

                            <v-btn type="submit" color="primary">
                                Register
                            </v-btn>
                            &nbsp;
                            <v-btn to="/login" color="secondary">Login</v-btn>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
<script setup>
import { reactive } from "vue";
import { useRouter } from "vue-router";
import axios from "axios";
// export default {
//     setup() {
const router = useRouter();
const user = reactive({
    name: "",
    email: "",
    password: "",
    confirmpassword: "",
});

const nameRules = [
    (v) => !!v || "Name is required",
    (v) => (v && v.length <= 50) || "Name must be less than 50 characters",
];

const emailRules = [
    (v) => !!v || "E-mail is required",
    (v) => /.+@.+\..+/.test(v) || "E-mail must be valid",
];

const passwordRules = [
    (v) => !!v || "Password is required",
    (v) => (v && v.length >= 8) || "Password must be at least 8 characters",
];

const confirmPasswordRules = [
    (v) => !!v || "Confirm Password is required",
    (v) => v === user.password || "Passwords do not match",
];

const register = async () => {
    try {
        // const isValid = await validate();
        // if (isValid) {
        const response = await axios.post("/api/auth/register", user);
        const { accessToken, userData } = response.data;
        authStore.setToken(accessToken);
        authStore.setUser(userData);
        // localStorage.setItem('userData', JSON.stringify(userData))
        // localStorage.setItem('accessToken', JSON.stringify(accessToken))
        router.push({ path: "/login" });
        // }
    } catch (error) {
        console.error("Error storing user data:", error);
    }
};
// return {
//     register,
//     user,
//     nameRules,
//     emailRules,
//     passwordRules,
//     confirmPasswordRules,
// };
//     },
// };
</script>

<!-- <route lang="yaml">
meta:
    layout: false
</route> -->
