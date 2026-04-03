/**
 * API Utility function for data fetching
 */

const BASE_URL = 'http://localhost:8000/api'; // Update with actual backend URL

export const fetchData = async (endpoint, options = {}) => {
  const { method = 'GET', body = null, headers = {}, ...customOptions } = options;

  const defaultHeaders = {
    'Accept': 'application/json',
  };

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

  try {
    const url = `${BASE_URL}${endpoint}`;
    const response = await fetch(url, config);
    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message || 'Something went wrong');
    }

    return data;
  } catch (error) {
    console.error('Fetch error:', error);
    throw error;
  }
};

export const getAPI = (endpoint) => fetchData(endpoint, { method: 'GET' });
export const postAPI = (endpoint, body) => fetchData(endpoint, { method: 'POST', body });
export const putAPI = (endpoint, body) => fetchData(endpoint, { method: 'PUT', body });
export const deleteAPI = (endpoint) => fetchData(endpoint, { method: 'DELETE' });
