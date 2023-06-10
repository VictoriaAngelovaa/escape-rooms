async function request(method, url, payload) {
    let response;
    if (method === 'GET') {
        response = await fetch(url, { method: method });
    }
    else {
        response = await fetch(url, { method: method, body: payload });
        console.log(response);
    }

    const body = await response.json(); 
    return {
        status: response.status,
        ok: response.ok,
        body
    }
}

export async function get(url) {
    return await request('GET', url);
}

export async function post(url, payload) {
    return await request('POST', url, payload);
}

export async function deleteRequest(url, payload = {}) {
    return await request('DELETE', url, payload);
}
