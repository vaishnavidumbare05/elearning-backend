const express = require('express');
const cors = require('cors');
const mongoose = require('mongoose');
const bcrypt = require('bcrypt');
const app = express();
const PORT = 5000;

// MongoDB connection
mongoose.connect('mongodb://localhost:27017/learning', { // updated to use 'learning'
    useNewUrlParser: true,
    useUnifiedTopology: true,
})
    .then(() => console.log("MongoDB connected"))
    .catch(err => console.error("MongoDB connection error:", err));

// Middleware
app.use(cors());
app.use(express.json()); // To handle JSON data

// User model
const User = mongoose.model('User', new mongoose.Schema({
    firstName: { type: String, required: true },
    lastName: { type: String, required: true },
    email: { type: String, required: true, unique: true },
    password: { type: String, required: true },
    newsletter: { type: Boolean, default: true },
}));

// Define a root route
app.get('/', (req, res) => {
    res.send('Welcome to the backend API!');
});

// Registration route
app.post('/api/register', async (req, res) => {
    const { firstName, lastName, email, password, newsletter } = req.body;
    try {
        const existingUser = await User.findOne({ email });
        if (existingUser) return res.status(400).json({ message: 'User already exists' });

        const hashedPassword = await bcrypt.hash(password, 10);
        const newUser = new User({
            firstName,
            lastName,
            email,
            password: hashedPassword,
            newsletter,
        });

        await newUser.save();
        res.status(201).json({ status: 'success', message: 'User registered successfully' });
    } catch (error) {
        console.error("Registration error:", error);  // Log the error details
        res.status(500).json({ status: 'error', message: 'Error registering user', error });
    }
});


// Login route
app.post('/api/login', async (req, res) => {
    const { email, password } = req.body;
    try {
        const user = await User.findOne({ email });
        if (!user) return res.status(404).json({ message: 'User not found' });

        const isMatch = await bcrypt.compare(password, user.password);
        if (!isMatch) return res.status(400).json({ message: 'Invalid credentials' });

        res.json({ message: 'Login successful' });
    } catch (error) {
        res.status(500).json({ message: 'Error during login', error });
    }
});

// Start the server
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
