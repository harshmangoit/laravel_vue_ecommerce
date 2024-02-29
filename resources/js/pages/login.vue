<template>
    <v-container class="mt-10 mb-10">
        <v-row justify="center">
            <v-col cols="12" sm="8" md="6">
                <v-card>
                    <v-card-title>
                        <h2>Login</h2>
                        <v-divider></v-divider>
                    </v-card-title>

                    <v-card-text>
                        <v-form @submit.prevent="login">
                            <v-text-field
                                v-model="user.email"
                                label="Email"
                                type="email"
                                outlined
                            ></v-text-field>

                            <v-text-field
                                v-model="user.password"
                                label="Password"
                                type="password"
                                outlined
                            ></v-text-field>

                            <div class="flex justify-between">
                                <div class="flex items-center">
                                    <v-btn type="submit" color="primary mr-2">
                                        Login
                                    </v-btn>
                                    <VBtn to="/register" color="secondary"
                                        >Register</VBtn
                                    >
                                </div>
                                <!-- <span class="mt-1">
                                    <GoogleLogin :callback="callback" :clientId="clientId">
                                    </GoogleLogin>
                                </span> -->
                            </div>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- Auth Sign In Section -->
        <v-row justify="center" class="text-center">
            <!-- <v-btn
                @click="signInGoogle"
                class="mt-2 mr-4"
                color="white"
                outlined
            >
                <v-icon size="24">mdi-google</v-icon>
                <span class="ml-2">Sign in with Google</span>
            </v-btn> -->
            <div class="mt-2">
                <GoogleLogin :callback="callback" />
            </div>
            <v-btn @click="signInGithub" class="mt-2 ml-2" color="white" outlined>
                <v-icon size="24">mdi-github</v-icon>
                <span class="ml-2">Sign in with GitHub</span>
            </v-btn>
        </v-row>
    </v-container>
</template>

<script setup>
import { ref, reactive } from "vue";
import { useRouter } from "vue-router";
import { useAuthStore } from "../stores/auth";
import { GoogleLogin, googleSdkLoaded } from "vue3-google-login";
import axios from "axios";
import { VBtn } from "vuetify/lib/components/index.mjs";
const router = useRouter();
const authStore = useAuthStore();
const user = reactive({
    email: "",
    password: "",
});
const clientId =
    "897640949480-kq9nt5kqtj4p5ugp2ns20mrqrilruc79.apps.googleusercontent.com";
const callback = (response) => {
    console.log("Handle the response", response);
};

const userDetails = ref("");
const getToken = async () => {
    await axios.get("/sanctum/csrf-cookie");
};
const login = async () => {
    await getToken();
    try {
        const response = await axios.post("/api/auth/login", user);
        const { accessToken, userData } = response.data;
        authStore.setToken(accessToken);
        authStore.setUser(userData);
        if (userData.role == 1) {
            router.replace({ name: "admin-product" });
        } else {
            router.replace({ path: "/" });
        }
    } catch (error) {
        console.error("Admin Login Errors:", error);
    }
};

async function sendCodeToBackend(code) {
    try {
        const response = await axios.post(
            "https://oauth2.googleapis.com/token",
            {
                code,
                client_id:
                    "897640949480-kq9nt5kqtj4p5ugp2ns20mrqrilruc79.apps.googleusercontent.com",
                client_secret: "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",
                redirect_uri: "postmessage",
                grant_type: "authorization_code",
            }
        );

        const accessToken = response.data.access_token;
        authStore.setToken(accessToken);

        // Fetch user details using the access token
        const userResponse = await axios.get(
            "https://www.googleapis.com/oauth2/v3/userinfo",
            {
                headers: {
                    Authorization: `Bearer ${accessToken}`,
                },
            }
        );
        console.log(userResponse);

        if (userResponse && userResponse.data) {
            // Set the userDetails data property to the userResponse object
            userDetails.value = userResponse.data;
            authStore.setUser(userDetails.value);
            if (userDetails.value.role == 1) {
                router.replace({ name: "admin-product" });
            } else {
                router.replace({ path: "/" });
            }
        } else {
            // Handle the case where userResponse or userResponse.data is undefined
            console.error("Failed to fetch user details.");
        }
    } catch (error) {
        console.error("Token exchange failed:", error.response.data);
    }
}

function signInGoogle() {
    googleSdkLoaded((google) => {
        google.accounts.oauth2
            .initCodeClient({
                client_id:
                    "897640949480-kq9nt5kqtj4p5ugp2ns20mrqrilruc79.apps.googleusercontent.com",
                scope: "email profile openid",
                redirect_uri: "http://localhost:4000/auth/callback",
                callback: (response) => {
                    if (response.code) {
                        sendCodeToBackend(response.code);
                    }
                },
            })
            .requestCode();
    });
}

function signInGithub() {
    const clientId = "bd65222f1fafd514ef30";
    const redirectUri = "http://127.0.0.1:8000/login"; // Set this to your callback URL
    const githubAuthUrl = `https://github.com/login/oauth/authorize?client_id=${clientId}&redirect_uri=${redirectUri}&scope=user`;

    window.location.href = githubAuthUrl;
}
</script>

<!-- <route lang="yaml">
meta:
    layout: false
</route> -->
