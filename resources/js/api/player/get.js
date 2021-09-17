import axios from 'axios'

export default async function(cookie) {
  let status = 0
  let message = ''
  let players = []

  await axios
    .get(process.env.MIX_APP_URL + '/api/players',
      {
        headers: {
          Authorization: 'Bearer ' + cookie //the token is a variable which holds the token
        }
      })
    .then(response => {
      status = response.status
      message = response.data.message
      if (status === 200) {
        players = response.data.players
      }
    })
    .catch(function(error) { // 请求失败处理
      status = error.response.status
      message = error.response.data.message
    })

  return {
    status,
    message,
    players,
  }
}