import React, { useEffect, useState } from 'react';

const API_URL = '/api/translation-units'; // Приклад endpoint-у

function App() {
  const [units, setUnits] = useState([]);
  const [newSource, setNewSource] = useState('');
  const [newTarget, setNewTarget] = useState('');
  const [editId, setEditId] = useState(null);

  useEffect(() => {
    fetch(API_URL + '?limit=10')
      .then((res) => res.json())
      .then((data) => setUnits(data));
  }, []);

  const handleAdd = () => {
    fetch(API_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ source: newSource, target: newTarget })
    })
      .then((res) => res.json())
      .then((newUnit) => {
        setUnits([newUnit, ...units.slice(0, 9)]);
        setNewSource('');
        setNewTarget('');
      });
  };

  const handleUpdate = (id) => {
    const unit = units.find((u) => u.id === id);
    fetch(`${API_URL}/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(unit)
    }).then(() => setEditId(null));
  };

  const handleChange = (id, field, value) => {
    setUnits((prev) =>
      prev.map((u) => (u.id === id ? { ...u, [field]: value } : u))
    );
  };

  return (
    <div style={{ padding: '2rem', fontFamily: 'Arial' }}>
      <h2>Translation Units</h2>

      <div style={{ marginBottom: '1rem' }}>
        <input
          placeholder="Source"
          value={newSource}
          onChange={(e) => setNewSource(e.target.value)}
        />
        <input
          placeholder="Target"
          value={newTarget}
          onChange={(e) => setNewTarget(e.target.value)}
        />
        <button onClick={handleAdd}>Add</button>
      </div>

      {units.map((unit) => (
        <div key={unit.id} style={{ marginBottom: '1rem' }}>
          {editId === unit.id ? (
            <>
              <input
                value={unit.source}
                onChange={(e) =>
                  handleChange(unit.id, 'source', e.target.value)
                }
              />
              <input
                value={unit.target}
                onChange={(e) =>
                  handleChange(unit.id, 'target', e.target.value)
                }
              />
              <button onClick={() => handleUpdate(unit.id)}>Save</button>
            </>
          ) : (
            <>
              <span>
                <b>Source:</b> {unit.source}
              </span>{' '}
              |{' '}
              <span>
                <b>Target:</b> {unit.target}
              </span>
              <button onClick={() => setEditId(unit.id)}>Edit</button>
            </>
          )}
        </div>
      ))}
    </div>
  );
}

export default App;
