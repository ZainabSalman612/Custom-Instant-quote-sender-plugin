# Instant Quote Estimator with Lead Capture

A custom WordPress plugin that provides a mobile-friendly frontend calculator for construction/landscaping services (Patio, Driveway, Garden Renovation). It dynamically calculates estimates based on area, and captures the user's details for lead generation.

## Features

- **Frontend Estimator Calculator**: Clean, mobile-friendly interface for calculating quotes based on service type.
- **Dynamic Conditional Fields**: Only displays relevant options (like Material Type for Patios or Renovation Level for Gardens).
- **Lead Capture Form**: Requires users to submit their Name, Email, and Phone to generate the email quote.
- **Customizable Pricing**: Edit all base pricing directly from the WordPress Admin Dashboard.
- **Design Customizer**: Full styling control over colors, typography, buttons, and layout directly from the Admin settings. No CSS knowledge required!
- **Email Notifications**: Generates an automated, professional HTML email to both the Customer and the Admin upon calculation.
- **Database Storage & Management**: Saves leads directly into a custom WordPress database table and allows you to read, sort, and export leads to CSV from the "Quote Leads" menu.
- **Performant**: Uses AJAX for submission to ensure there are no page reloads, and outputs minimal dynamic CSS.

## Installation

1. Upload or copy the `Custom-Instant-quote-sender-plugin` folder into your WordPress installation's `wp-content/plugins/` directory.
2. Log into your WordPress Dashboard.
3. Navigate to **Plugins > Installed Plugins**.
4. Find **Instant Quote Estimator with Lead Capture** and click **Activate**.

## Usage

1. **Configure Pricing:** After activation, go to **Quote Leads > Settings** and click on the **Pricing** tab to set your base rates for materials and services.
2. **Configure Design:** Navigate to the **Design & Styling** tab to match the plugin's frontend calculator to your theme's aesthetics. 
3. **Configure Notifications:** Navigate to the **Emails** tab to set up the sender and admin recipient addresses.
4. **Display the Calculator:** To show the calculator to your users, edit any page or post and insert the following shortcode where you want it to appear:
   
   ```text
   [instant_quote_estimator]
   ```

5. **Manage Leads:** As users generate quotes, their data is logged. Go to the **Quote Leads** top-level menu in the sidebar to review past estimates and use the **Export to CSV** button to download the data for external CRM tools.
