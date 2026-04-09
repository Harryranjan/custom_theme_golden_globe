# 🚀 Golden Globe: Theme Usage Guide

Welcome to your new **Plugin-Free Agency Theme**! This theme is designed for high performance and full flexibility. Here is how to use the "Universal Builder" system to create your pages.

---

## 1. Creating a New Page
1.  In your WordPress Dashboard, go to **Pages > Add New**.
2.  On the right-hand sidebar, find **Page Attributes**.
3.  Select a **Template**:
    *   **Home Page**: For your primary high-impact landing page.
    *   **About Page**: Built-in storytelling flow (History, Values, Stats).
    *   **Sections Builder**: A blank canvas where YOU decide the section order.

---

## 2. Using the "Sections Builder"
The **Sections Builder** field allows you to stack components in any order. Currently, this version uses **JSON format** for maximum lightweight performance.

### How to add a section:
Copy and paste a snippet like this into the **"Sections Builder (JSON)"** box:

#### 🟢 Simple Features Grid:
```json
[
  {
    "layout": "features-grid",
    "title": "Why Choose Us",
    "items": [
      { "name": "Expert Team", "desc": "Top-tier developers.", "icon_name": "shield" },
      { "name": "Fast Delivery", "desc": "We ship projects on time.", "icon_name": "target" }
    ]
  }
]
```

#### 🔵 Portfolio Showcase:
```json
[
  {
    "layout": "portfolio-showcase",
    "title": "Our Latest Projects",
    "count": 4
  }
]
```

#### 🟣 Multiple Sections (Combined):
```json
[
  { "layout": "counter-stats", "stats": [{"number": "150", "label": "Clients", "suffix": "+"}] },
  { "layout": "cta-banner", "title": "Ready to Scale?", "bg_color": "#2563eb" }
]
```

---

## 3. Dynamic Branding Dashboard
You can change the global look of the theme without touching any code:
1.  Go to **Theme Options** (left sidebar).
2.  Choose your **Primary**, **Secondary**, and **Accent** colors.
3.  Select your **Typography** (Sans-Serif for UI, Serif for Headings).
4.  Choose your **Visual Style** (Sharp, Rounded, or Pill-style corners).
5.  Click **Save Changes**. The theme updates instantly!

---

## 4. Contact Form Configuration
To start receiving emails from your contact form:
1.  In **Theme Options**, scroll down to **Contact Form Settings**.
2.  Enter the **Recipient Email**.
3.  Customize the **Success** and **Error** messages.
4.  The form appears automatically on the **About** and **Contact** pages.

---

### Standard Slugs You Can Use:
`hero`, `features-grid`, `cta-banner`, `image-text`, `portfolio-showcase`, `services-list`, `contact-section`, `testimonials`, `client-logos`, `team-highlights`, `pricing`, `values-grid`, `counter-stats`.
