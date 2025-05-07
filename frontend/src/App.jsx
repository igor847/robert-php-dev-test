import React, { useEffect, useState } from 'react';

const API_URL = '/api/segments';

function App() {
  const [segments, setSegments] = useState([]);
  const [segment, setSegment] = useState('');
  const [translation, setTranslation] = useState('');
  const [editId, setEditId] = useState(null);

  useEffect(() => {
    fetch(API_URL)
      .then((res) => res.json())
      .then((data) => setSegments(data));
  }, []);

  const handleAdd = (e) => {
    e.preventDefault();
    fetch(API_URL, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({
        segment,
        translation
      })
    })
      .then((res) => res.json())
      .then((newSegment) => {
        setSegments([newSegment, ...segments.slice(0, 9)]);
        setSegment('');
        setTranslation('');
      });
  };

  const handleUpdate = (id) => {
    const unit = segments.find((u) => u.id === id);
    fetch(`${API_URL}/${id}`, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(unit)
    }).then(() => setEditId(null));
  };

  const handleChange = (id, field, value) => {
    setSegments((prev) =>
      prev.map((u) => (u.id === id ? { ...u, [field]: value } : u))
    );
  };

  return (
    <div>
      <main class="container">
        <form class="my-3 p-3 bg-body rounded shadow">
          <h1 class="h3 mb-3 fw-normal">Add translate</h1>

          <div className="row">
            <div className="col-6">
              <label for="segment" class="form-label">
                Segment
              </label>
              <select class="form-select mb-1">
                <option selected>Language...</option>
                <option value="en" selected>
                  English
                </option>
                <option value="uk">Ukrainian</option>
              </select>
              <textarea
                class="form-control"
                cols="30"
                id="segment"
                placeholder="Segment"
                value={segment}
                onChange={(e) => setSegment(e.target.value)}
              ></textarea>
            </div>
            <div className="col-6">
              <label for="translate" class="form-label">
                Translate
              </label>
              <select class="form-select mb-1">
                <option selected>Language...</option>
                <option value="en">English</option>
                <option value="uk" selected>
                  Ukrainian
                </option>
              </select>
              <textarea
                class="form-control"
                cols="30"
                id="translate"
                placeholder="Translate"
                value={translation}
                onChange={(e) => setTranslation(e.target.value)}
              ></textarea>
            </div>
          </div>

          <button
            class="btn btn-primary w-100 py-2 mt-3"
            type="submit"
            onClick={handleAdd}
          >
            Add
          </button>
        </form>

        <div class="my-3 p-3 bg-body rounded shadow">
          <h6 class="border-bottom pb-2 mb-0">Translates (last 10)</h6>

          {segments.map((unit) => (
            <div key={unit.id} style={{ marginBottom: '1rem' }}>
              {editId === unit.id ? (
                <>
                  <div class="d-flex text-body-secondary pt-3">
                    <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                      <div className="row">
                        <div className="col-6 bg-body rounded shadow-sm">
                          <strong class="text-gray-dark">Segment:</strong>
                          <textarea
                            class="form-control"
                            cols="30"
                            placeholder="Segment"
                            value={unit.content}
                            onChange={(e) =>
                              handleChange(unit.id, 'segment', e.target.value)
                            }
                          ></textarea>
                        </div>
                        <div className="col-6 bg-body rounded shadow-sm">
                          <strong class="text-gray-dark">Translate:</strong>
                          <textarea
                            class="form-control"
                            cols="30"
                            placeholder="Translate"
                            value={unit.translation}
                            onChange={(e) =>
                              handleChange(
                                unit.id,
                                'translation',
                                e.target.value
                              )
                            }
                          ></textarea>
                        </div>
                      </div>
                      <div className="text-end">
                        <span
                          className="btn btn-primary"
                          onClick={() => handleUpdate(unit.id)}
                        >
                          save
                        </span>
                      </div>
                    </div>
                  </div>
                </>
              ) : (
                <>
                  <div class="d-flex text-body-secondary pt-3">
                    <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                      <div className="row">
                        <div className="col-6 bg-body rounded shadow-sm">
                          <strong class="text-gray-dark">Segment:</strong>
                          <p>{unit.content}</p>
                        </div>
                        <div className="col-6 bg-body rounded shadow-sm">
                          <strong class="text-gray-dark">Translate:</strong>
                          <p>{unit.translation}</p>
                        </div>
                      </div>
                      <div className="text-end">
                        <span
                          className="btn btn-warning"
                          onClick={() => setEditId(unit.id)}
                        >
                          edit
                        </span>
                      </div>
                    </div>
                  </div>
                </>
              )}
            </div>
          ))}

          <small class="d-block text-end mt-3">
            <a href="#">Show all translates</a>
          </small>
        </div>
      </main>
    </div>
  );
}

export default App;
