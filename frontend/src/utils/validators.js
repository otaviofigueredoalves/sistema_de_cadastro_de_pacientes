export function validateCpf(val) {
  if (!val) return false
  const cpf = val.replace(/\D/g, '')
  if (cpf.length !== 11 || !!cpf.match(/(\d)\1{10}/)) return false
  let sum = 0, rest
  for (let i = 1; i <= 9; i++) sum = sum + parseInt(cpf.substring(i - 1, i)) * (11 - i)
  rest = (sum * 10) % 11
  if ((rest === 10) || (rest === 11)) rest = 0
  if (rest !== parseInt(cpf.substring(9, 10))) return false
  sum = 0
  for (let i = 1; i <= 10; i++) sum = sum + parseInt(cpf.substring(i - 1, i)) * (12 - i)
  rest = (sum * 10) % 11
  if ((rest === 10) || (rest === 11)) rest = 0
  if (rest !== parseInt(cpf.substring(10, 11))) return false
  return true
}

export function validateCns(val) {
  if (!val) return false
  const cns = val.replace(/\D/g, '')
  if (cns.length !== 15) return false
  
  const validateDefinitive = (cns) => {
    let pis = cns.substring(0, 11)
    let sum = 0
    for (let i = 0; i < 11; i++) sum += parseInt(pis[i]) * (15 - i)
    let rest = sum % 11
    let dv = 11 - rest
    if (dv === 11) dv = 0
    let result = ''
    if (dv === 10) {
      sum = 0
      pis = cns.substring(0, 11) + '001'
      for (let i = 0; i < 14; i++) sum += parseInt(pis[i]) * (15 - i)
      rest = sum % 11
      dv = 11 - rest
      result = pis + dv
    } else {
      result = pis + '000' + dv
    }
    return cns === result
  }

  const validateProvisional = (cns) => {
    let sum = 0
    for (let i = 0; i < 15; i++) sum += parseInt(cns[i]) * (15 - i)
    return sum % 11 === 0
  }

  if (['1', '2'].includes(cns[0])) return validateDefinitive(cns)
  if (['7', '8', '9'].includes(cns[0])) return validateProvisional(cns)
  return false
}

export function validatePastDate(val) {
  if (!val) return false
  const todayStr = new Date().toISOString().split('T')[0]
  return val <= todayStr
}
