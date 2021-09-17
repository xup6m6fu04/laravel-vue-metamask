import axios from 'axios'

export default async function(cookie) {
  let status = 0
  let message = ''
  let player = ''

  await axios
    .post(process.env.MIX_APP_URL + '/api/player',
      {}, {
        headers: {
          Authorization: 'Bearer ' + cookie //the token is a variable which holds the token
        }
      })
    .then(response => {
      status = response.status
      message = response.data.message
      if (status === 200) {
        player = response.data.player
      }
    })
    .catch(function(error) { // 请求失败处理
      status = error.response.status
      message = error.response.data.message
    })

  return {
    status,
    message,
    player,
  }
}