import customOptions from './object.js';
import { addPrefix } from '../../functions/addPrefix.js';

export default ({ addComponents, prefix = '' }) => {
  const prefixedcustomOptions = addPrefix(customOptions, prefix);
  addComponents({ ...prefixedcustomOptions });
};
