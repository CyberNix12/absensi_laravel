import validation from './object.js';
import { addPrefix } from '../../functions/addPrefix.js';

export default ({ addComponents, prefix = '' }) => {
  const prefixedvalidation = addPrefix(validation, prefix);
  addComponents({ ...prefixedvalidation });
};
