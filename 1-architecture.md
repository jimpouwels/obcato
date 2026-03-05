**Architecture of Obcato:**

Obcato is designed to be a simple, modular, multi-language web content management system. The architecture is intended to facilitate minimal technical efforts while helping users build websites efficiently.

1. **Platform Overview:**
   - Obcato is based on PHP and requires a MySQL database for operations.
   - Designed to handle both frontend presentation and backend management.

2. **Key Components:**
   - **Frontend:** 
     The frontend leverages templating through the Smarty template engine. This enables users to design their web presence while interacting through Obcato's web-based user interface (WebUI).
     
     Templates link data (such as articles and images) to render complete HTML pages. These templates are uploaded and operate within `<PRIVATE_DIR>/templates`.
   
   - **Backend:** 
     The backend provides content management functionalities, ensuring seamless interaction between user data and visual output. It includes modules to manage authentication and user roles, handle requests, and load modularized components.

3. **Modularity:**
   - Obcato is highly modular, making it adaptable and extensible.
   - Core modules, such as pages, articles, and webforms, allow users to create and manage diverse web content types efficiently.
   - Developers can add new modules to expand the system’s capabilities.

4. **File Structure:**
   - **Public Section:** Located in the web hosting’s public directory, e.g., `httpd.www`. It includes entry-point files like `index.php` to render the frontend.
   - **Private Section:** All Obcato core files, templates, and backend components reside in the private directory.

5. **Bootstrap Initialization:**
   The `src/bootstrap.php` file:
   - Sets up critical constants (`PUBLIC_ROOT`, `PRIVATE_ROOT`, etc.).
   - Loads necessary configurations and modules.
   - Initializes friendly URLs through `.htaccess` for efficient routing.

6. **Development and Testing:**
   - Obcato supports development and testing modes through utilities in its private directory.
   - Mocks and test configurations are stored under `test/` to assist in unit testing and debugging.

This detailed architecture overview ensures that developers can understand and extend Obcato effectively. Feedback or questions about this documentation are welcome.