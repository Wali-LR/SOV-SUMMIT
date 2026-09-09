# SOV Summit — Coming Soon Page

A single-page "coming soon" holding page for SOV Summit.

## Structure

```
sov-summit/
├── index.html          # the page itself
├── assets/
│   └── img/
│       └── logo.png    # SOV Summit wordmark (transparent PNG)
└── README.md
```

## Running locally

No build step — it's plain HTML/CSS. Just open `index.html` in a browser,
or serve the folder locally:

```bash
python3 -m http.server 8000
```

Then visit `http://localhost:8000`.

## Deploying with GitHub Pages

1. Push this repo to GitHub.
2. Go to **Settings → Pages**.
3. Under **Build and deployment**, set **Source** to `Deploy from a branch`,
   pick the `main` branch and `/ (root)` folder.
4. Save — GitHub will publish the site at
   `https://<your-username>.github.io/<repo-name>/`.

## Notes

- Fonts (Fraunces, JetBrains Mono) load from Google Fonts via CDN.
- The logo doubles as the favicon and social-preview (Open Graph) image.
