import axios from 'axios'

export default async function(accounts, cookie) {
  let status = 0
  let message = ''
  let resp = ''

  await axios
    .post(process.env.MIX_APP_URL + '/api/auth/me',
      {}, {
        headers: {
          Authorization: 'Bearer ' + cookie //the token is a variable which holds the token
        }
      })
    .then(response => {
      status = response.status
      message = response.data.message
      if (status === 200 && response.data.address === accounts) {
        resp = response.data
      }
    })
    .catch((error) => {
      console.log(error)
    })

  return {
    status,
    message,
    resp
  }
}