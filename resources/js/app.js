require("./bootstrap");

import { createApp } from "vue";
import App from "./app.vue";

const app = createApp({
    components: {
        App,
    }
});

app.mount("#app");
