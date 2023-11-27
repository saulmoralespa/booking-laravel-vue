export const clientFetch = async (method = 'GET', id, payload ) => {
    try {
        return await fetch(`api/bookings/${id}`,
            {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(payload)
            },
        ).then(response => response.json());
    }catch (error){
        console.log(error)
    }
}
