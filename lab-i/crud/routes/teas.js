const express = require('express');
const router = express.Router();
const { DatabaseSync } = require('node:sqlite');
const path = require('path');
const db = new DatabaseSync(path.resolve(__dirname, '../db.sqlite'));

router.get('/', (req, res) => {
    const teas = db.prepare('SELECT * FROM teas').all();
    res.render('teas/index', { title: 'Tea List', teas });
});

router.get('/create', (req, res) => {
    res.render('teas/create', { title: 'Create Tea' });
});

router.post('/create', (req, res) => {
    const { name, type, description } = req.body.tea;
    db.prepare('INSERT INTO teas (name, type, description) VALUES (?, ?, ?)').run(name, type, description);
    res.redirect('/teas');
});

router.get('/:id', (req, res) => {
    const tea = db.prepare('SELECT * FROM teas WHERE id = ?').get(req.params.id);
    if (!tea) return res.status(404).send('Not found');
    res.render('teas/show', { title: 'Tea Details', tea });
});

router.get('/:id/edit', (req, res) => {
    const tea = db.prepare('SELECT * FROM teas WHERE id = ?').get(req.params.id);
    res.render('teas/edit', { title: 'Edit Tea', tea });
});

router.post('/edit', (req, res) => {
    const { id, name, type, description } = req.body;
    db.prepare('UPDATE teas SET name=?, type=?, description=? WHERE id=?').run(name, type, description, id);
    res.redirect('/teas');
});

router.post('/delete', (req, res) => {
    db.prepare('DELETE FROM teas WHERE id = ?').run(req.body.id);
    res.redirect('/teas');
});

module.exports = router;