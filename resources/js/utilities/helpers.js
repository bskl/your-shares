export function parseErrors(error) {
  const errors = error.response.data.errors || [];

  if (errors.length == 0) return '';

  return errors[0][0];
}