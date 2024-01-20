const conn = new WebSocket('ws://localhost:8080')

conn.addEventListener('open', (event) => {
    console.log('CONNECTED')
})

conn.addEventListener('message', (event) => {
    console.log(event.data)
})

conn.addEventListener('close', () => {
    console.log("CONNECTION CLOSED")
})
