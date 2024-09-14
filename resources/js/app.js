import './bootstrap';
import 'highlight.js/styles/atom-one-dark.css'; // Import a Highlight.js style

export function global() {
    const DARK_LOGO = 'logo-white.png';
    const LIGHT_LOGO = 'logo.png';

    return {
      isMobileMenuOpen: false,
      isDarkMode: false,
      logoImg: LIGHT_LOGO,
      themeInit() {
        if (
          localStorage.theme === "dark" ||
          (!("theme" in localStorage) &&
            window.matchMedia("(prefers-color-scheme: dark)").matches)
        ) {
          localStorage.theme = "dark";
          document.documentElement.classList.add("dark");
          this.isDarkMode = true;
          this.logoImg = DARK_LOGO;
        } else {
          localStorage.theme = "light";
          document.documentElement.classList.remove("dark");
          this.isDarkMode = false;
          this.logoImg = LIGHT_LOGO;
        }
      },
      themeSwitch() {
        if (localStorage.theme === "dark") {
          localStorage.theme = "light";
          document.documentElement.classList.remove("dark");
          this.isDarkMode = false;
          this.logoImg = LIGHT_LOGO;
        } else {
          localStorage.theme = "dark";
          document.documentElement.classList.add("dark");
          this.isDarkMode = true;
          this.logoImg = DARK_LOGO;
        }
      },
    };
}

window.global = global
