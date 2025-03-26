require('dotenv').config();
const express = require('express');
const bodyParser = require('body-parser');
const { Pool } = require('pg');

const app = express();
const port = 3000;

// PostgreSQL Connection (Supabase)
const pool = new Pool({
    connectionString: process.env.DATABASE_URL, // Store in .env file
    ssl: { rejectUnauthorized: false } // Required for Supabase
});

// Middleware
app.use(bodyParser.urlencoded({ extended: true }));
app.use(express.static("public")); // Serve static files
app.set("view engine", "ejs"); // Optional: Use EJS for rendering responses

// Route: Handle form submission
app.post('/submit', async (req, res) => {
    const { name, email, subject, message } = req.body;

    if (!name || !email || !message) {
        return res.render("response", { status: "error", message: "Please fill in all required fields." });
    }

    try {
        const result = await pool.query(
            "INSERT INTO portfolio_db.contact_form (name, email, subject, message) VALUES ($1, $2, $3, $4) RETURNING *",
            [name, email, subject || 'No Subject', message]
        );

        res.render("response", { status: "success", message: "Your message has been sent successfully!" });
    } catch (error) {
        console.error("Database Error:", error);
        res.render("response", { status: "error", message: "Something went wrong. Please try again." });
    }
});

// Redirect if accessed directly
app.get('/', (req, res) => {
    res.redirect('/contact.html');
});

// Start server
app.listen(port, () => {
    console.log(`Server running on http://localhost:${port}`);
});
