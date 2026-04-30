## About https://www.humansofphnompenh.com as of April 27, 2026 (old version)

Humans of Phnom Penh (accessible at www.humansofphnompenh.com) is a platform dedicated to showcasing the rich diversity and culture of Phnom Penh, Cambodia. The website operates on the core belief that everyone has a story worth sharing, aiming to build bridges between communities and foster a space for empathy, dialogue, and understanding.

### Core Content and Mission

The website is designed to capture the city's unique blend of tradition—reflected in its history, architecture, cuisine, and customs—and its rapid modernization. It accomplishes this through a variety of mediums:

- Interviews: The site regularly features interviews with people from all walks of life, including everyday residents, artists, entrepreneurs, and activists, to gain insight into their passions and perspectives.
- Visual Media: These stories are paired with high-quality photographs and videos that highlight various facets of the city, from bustling markets to peaceful riverside neighborhoods.

### Target Audience

The platform is aimed at both lifelong local residents and curious visitors, inviting them to explore and develop a deeper appreciation for the city's past, present, and future.

Website Structure and Features
Based on the website's layout, it offers several ways for users to interact and navigate:

- Main Menu: The navigation bar includes links to _Home_, _About Us_, _Products_, _Stories_, _Artist_, _Career_, and _Contact Us_.
- Unique Offerings: The site features a specific section called "Pitch Your Pal: Phnom Penh" and appears to have an e-commerce element, indicated by the "Products" page and a shopping cart.
- Footer: The bottom of the webpage includes simplified navigation (Home, About Us, Stories, Get in Touch), four social media "Follow" links, a 2023 copyright notice, and standard links to their Privacy Policy and Terms and Conditions.

## Tech Stack

The current website has been built with wordpress. The e-commerce element probably implements some wp plugins. I haven't been able to access the admin console yet, so I cannot be certain which libraries/dependencies/plug-ins are used.

## New version

The website needs updating significantly.

UI/UX Design in Google Stitch: https://stitch.withgoogle.com/projects/15221286347748704797

## Side Note

I don't think it's a good idea to change the tech stack entirely, because I don't have access to the server. At most, I could only access the admin panel and figure out ways to improve the UI/UX from there. I have done a little research, saying I can create a new wp theme using HTML, CSS, and JS, but I have to be super careful! Replacing a new theme while not having access to the server is like doing operation on our own self. Once an error occurs, there is no way I can revert it. That's why I must create a docker-compose.yml, import the exact same wp contents from the website, develop the new UI/UX, then update the theme locally. Only after I am 100% sure the theme works perfectly will I integrate the change.
