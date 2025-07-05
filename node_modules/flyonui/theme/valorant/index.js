import valorant from './object.js';
import { addPrefix } from '../../functions/addPrefix.js';

export default ({ addBase, prefix = '' }) => {
  const prefixedvalorant = addPrefix(valorant, prefix);
  addBase({ ...prefixedvalorant });
};
