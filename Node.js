const express = require("express");
const router = express.Router();
const db = require("./db");

// Get all questions
router.get("/questions", (req, res) => {
    db.query("SELECT * FROM questions", (err, results) => {
        if (err) return res.status(500).json({ error: "Database error" });
        res.json(results);
    });
});

// Post a new question
router.post("/questions", (req, res) => {
    const { title, content } = req.body;
    db.query("INSERT INTO questions (title, content) VALUES (?, ?)", [title, content], (err) => {
        if (err) return res.status(500).json({ error: "Database error" });
        res.json({ message: "Question posted successfully" });
    });
});

module.exports = router;
