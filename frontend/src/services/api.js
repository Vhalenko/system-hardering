const BASE_URL = 'http://localhost'

async function request(method, path, body = null) {
  const options = {
    method,
    headers: { 'Content-Type': 'application/json' },
    credentials: 'include',
  }
  if (body) options.body = JSON.stringify(body)

  const res = await fetch(`${BASE_URL}${path}`, options)
  const data = await res.json()

  if (!res.ok) throw new Error(data.message || 'Something went wrong')
  return data
}

export default {
  get:    (path)        => request('GET',    path),
  post:   (path, body)  => request('POST',   path, body),
  put:    (path, body)  => request('PUT',    path, body),
  delete: (path)        => request('DELETE', path),
}