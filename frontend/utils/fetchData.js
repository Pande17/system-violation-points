/**
 * API Utility function for data fetching
 * Includes automatic redirect to 404NotFound.html for HTTP 404 & 403 responses
 */

const BASE_URL = 'http://localhost:8000/api'; // Update with actual backend URL

/**
 * Redirect ke halaman 404/403 dengan menyertakan URL asal
 * @param {'not_found'|'unauthorized'} type
 */
function redirectToError(type = 'not_found') {
  const from = encodeURIComponent(window.location.pathname + window.location.search);
  // Hitung relative path ke 404NotFound.html dari posisi saat ini
  const depth = (window.location.pathname.match(/\//g) || []).length - 1;
  const prefix = depth > 1 ? '../'.repeat(depth - 1) : './';
  window.location.href = `${prefix}pages/404NotFound.html?type=${type}&from=${from}`;
}

export const fetchData = async (endpoint, options = {}) => {
  const { method = 'GET', body = null, headers = {}, skipErrorRedirect = false, ...customOptions } = options;

  const defaultHeaders = {
    'Accept': 'application/json',
  };

  // Sertakan token jika ada
  const token = localStorage.getItem('token');
  if (token) {
    defaultHeaders['Authorization'] = `Bearer ${token}`;
  }

  if (body instanceof FormData) {
    // Let browser set Content-Type with boundary for FormData
  } else if (body) {
    defaultHeaders['Content-Type'] = 'application/json';
  }

  const config = {
    method,
    headers: { ...defaultHeaders, ...headers },
    ...customOptions,
  };

  if (body && !(body instanceof FormData)) {
    config.body = JSON.stringify(body);
  } else if (body instanceof FormData) {
    config.body = body;
  }

  const url = `${BASE_URL}${endpoint}`;
  const response = await fetch(url, config);

  // ─── Handle HTTP Error: 404 dan 403 ────────────────────────────
  if (!skipErrorRedirect) {
    if (response.status === 404) {
      redirectToError('not_found');
      return null;
    }
    if (response.status === 403) {
      redirectToError('unauthorized');
      return null;
    }
  }

  const data = await response.json();

  // Kembalikan objek dengan status agar caller bisa handle error sendiri
  return { ...data, _httpStatus: response.status };
};

export const getAPI    = (endpoint, opts = {})       => fetchData(endpoint, { method: 'GET',    ...opts });
export const postAPI   = (endpoint, body, opts = {})  => fetchData(endpoint, { method: 'POST',   body, ...opts });
export const putAPI    = (endpoint, body, opts = {})  => fetchData(endpoint, { method: 'PUT',    body, ...opts });
export const deleteAPI = (endpoint, opts = {})        => fetchData(endpoint, { method: 'DELETE', ...opts });
