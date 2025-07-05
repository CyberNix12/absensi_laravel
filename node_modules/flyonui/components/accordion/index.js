import accordion from './object.js';
import { addPrefix } from '../../functions/addPrefix.js';

export default ({ addComponents, prefix = '' }) => {
  const prefixedaccordion = addPrefix(accordion, prefix);
  addComponents({ ...prefixedaccordion });
};
